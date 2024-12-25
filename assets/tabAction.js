document.addEventListener('DOMContentLoaded', () => {
    const button = document.getElementById('get-values-button');
    const link = document.getElementById('dynamic-link');
    const urlTemplate = link.getAttribute('data-url-template');
    link.style.visibility = "hidden"
    window.selectedRows = [];

    button.addEventListener('click', async () => {
        const checkboxes = document.querySelectorAll('.row-checkbox:checked');

        checkboxes.forEach(checkbox => {
            const row = checkbox.value; // Accède à la ligne
            window.selectedRows.push(row);
        });

        console.log('Lignes sélectionnées :', selectedRows);
        // alert(JSON.stringify(selectedRows, null, 2)); // Affiche les résultats*

        const request = await fetch('/task/new', {
            method: 'POST',
            body: JSON.stringify(window.selectedRows)
        })

        console.log(await request.json());
        




        link.style.visibility = "visible"
        window.dynamicValue = window.selectedRows.join(','); // Exemple : Une valeur calculée ou récupérée dynamiquement
        window.dynamicUrl = urlTemplate.replace('PLACEHOLDER', window.dynamicValue);
    
        // Mettez à jour l'attribut href avec l'URL générée
        link.setAttribute('href', window.dynamicUrl);
    });


    // Remplacez "PLACEHOLDER" par une valeur dynamique

    console.log('URL dynamique générée :', window.dynamicUrl);
});