function Export(tableID,filename = 'Vat-Reports') {

    // Specify file name
    filename = filename?filename+'.pdf':'pdf_data.pdf';

    html2canvas(document.getElementById(tableID), {
        onrendered: function (canvas) {
            var data = canvas.toDataURL();
            console.log(data)
            var docDefinition = {
                content: [{
                    image: data,
                    width: 500
                }]
            };
            pdfMake.createPdf(docDefinition).download(filename);
        }
    });
}
