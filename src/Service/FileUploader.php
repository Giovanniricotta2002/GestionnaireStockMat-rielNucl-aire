<?php

namespace App\Service;

use App\Entity\MaterielInspection;
use App\Enum\Status;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private Serializer $serializer;
    private string $fileName = '';
    private mixed $data = '';

    public function __construct(
        private string $targetDirectory,
        private SluggerInterface $slugger,
        private EntityManagerInterface $em,
    ) {
        $encoded = [new XmlEncoder(), new JsonEncode(), new CsvEncoder([';'])];
        $normalizers = [new ObjectNormalizer()];

        $this->serializer = new Serializer($normalizers, $encoded);
    }

    public function upload(UploadedFile $file): FileUploader
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.csv';

        try {
            $file->move($this->getTargetDirectory(), $newFilename);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        $this->fileName = $newFilename;

        return $this;
    }

    public function serializeFile(?string $string = null): FileUploader
    {

        $this->fileName = $string ?? $this->fileName;

        $data = [];
        
        if (($handle = fopen($this->getTargetDirectory().'/'.$this->getFileName(), 'r')) !== false) {
            // Lire les en-têtes
            $headers = explode(',', fgetcsv($handle, 1000, ';')[0]);
            
            // Lire les lignes suivantes
            for ($i = 0; ($rowRaw = fgetcsv($handle, 1000, ';')) !== false; $i++) {
                for ($j = 0; $j < count($headers); $j++) {
                    $row = explode(',', $rowRaw[0]);
                    $data[$i][$headers[$j]] = $row[$j];
                }
            }

            fclose($handle);
        }

        $this->data = $data;

        return $this;
    }

    public function importData(): FileUploader
    {
        foreach (array_chunk($this->getData(), 100) as $chunk) {
            foreach ($chunk as $data) {
                $mi = new MaterielInspection();
                $mi->setProductCol($data['product_col']);
                $mi->setDateIntsall(new \DateTime($data['date_install']));
                $mi->setDateInspect(new \DateTime($data['date_inspect']));
                $mi->setStatus(Status::from($data['status']));
                $mi->setDescription($data['description']);
                $this->em->persist($mi);
            }
            $this->em->flush();
            $this->em->clear();
        }

        return $this;
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getData(): mixed
    {
        return $this->data;
    }
}
