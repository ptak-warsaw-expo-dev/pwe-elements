<?php 
    $pdf = 'https://cleanexpo.pl/doc/invitation/zaproszenie_vip.pdf';
    $logo = 'https://cleanexpo.pl/doc/ssl.png';


?>
<html>
    <head>
      <meta charset="utf-8" />
      <script src="https://unpkg.com/pdf-lib@1.11.0"></script>
      <script src="https://unpkg.com/downloadjs@1.4.7"></script>
      <script src="https://unpkg.com/@pdf-lib/fontkit@0.0.4"></script>
      <!-- <link rel="stylesheet" href="../main.css"> -->
      <meta http-equiv="Cache-Control" content="no-cache">
      <meta http-equiv="Cache-Control" content="private">
      <meta http-equiv="Cache-Control" content="max-age=100, must-revalidate">
    </head>
  
    <body onload="modifyPdf()">
      <div class="container">
        <!-- <p id="nameid" class="line-1 anim-typewriter">Name</p> -->
        <!-- <button onclick="modifyPdf()" class="custom-btn btn-14">Pobierz PDF</button> -->
      </div>
    </body>

    <script>
       
        // const url_string = window.location.href;
        // const url = new URL(url_string);
        // const getname = url.searchParams.get("getname");
        // const firma = url.searchParams.get("firma");
        // const qrcode = url.searchParams.get("qrcode");
        // const category = url.searchParams.get("category");

        const { PDFDocument, rgb, StandardFonts } = PDFLib
        
        async function modifyPdf() {

            const existingPdfBytes = await fetch('<?php echo $pdf ?>').then(res => res.arrayBuffer())

            const pdfDoc = await PDFDocument.load(existingPdfBytes)

            //  dodaty error pry wybori bez pdf
            const ubuntuurl = 'https://warsawexpo.eu/Ubuntu-R.ttf'
            const ubuntu = await fetch(ubuntuurl).then(res => res.arrayBuffer())
            pdfDoc.registerFontkit(fontkit);
            const customFont = await pdfDoc.embedFont(ubuntu)

            //add options
            const qrCodeUrl = '<?php echo $logo ?>';
            const qrCodeBytes = await fetch(qrCodeUrl).then(res => res.arrayBuffer());
            const qrCodeImage = await pdfDoc.embedPng(qrCodeBytes);

            const form = pdfDoc.getForm();
            const pages = pdfDoc.getPages();
            const firstPage = pages[0];
            const secondPage = pages[1];
            const { width, height } = firstPage.getSize();

            pdfDoc.setTitle('Zaproszenie na Bankiet')
            pdfDoc.setAuthor('Ptak Warsaw Expo')
            pdfDoc.setSubject('Invite')
            pdfDoc.setKeywords(['Invite', 'Targi', 'PWE', 'Impreza', 'Fairs', 'Bankiet'])
            pdfDoc.setProducer('BadgeGenerator 1.5')
            pdfDoc.setCreator('Ptak Warsaw Expo (kontakt@warsawexpo.eu)')


            // 
            // // 60=5, 50=6, 40=7, 30=9, 20=11, 14
            // var textSizeFirma =  9;
            // if (firma.length > 60) {
            // var textSizeFirma = 5;
            // } else if (firma.length > 50){
            // var textSizeFirma = 6;
            // } else if (firma.length > 40){
            // var textSizeFirma = 7;
            // } else if (firma.length > 30){
            // var textSizeFirma = 9;
            // } else if (firma.length > 20){
            // var textSizeFirma = 11;
            // } else {
            // var textSizeFirma = 14;
            // }
            // console.log("Firm lenght: " + firma.length + ". Change FontSize: " + textSizeFirma);
            // var textSizeName = 20;
            // if (getname.length > 50) {
            // var textSizeName = 13;
            // } else if (getname.length > 40){
            // var textSizeName = 15;
            // } else if (getname.length > 30){
            // var textSizeName = 16;
            // } else if (getname.length > 25){
            // var textSizeName = 19;
            // } else {
            // var textSizeName = 20;
            // }
            // console.log("Name lenght: " + getname.length + ". Change FontSize: " + textSizeName);


            // const textWidth = customFont.widthOfTextAtSize(getname, textSizeName);
            // const textWidth2 = customFont.widthOfTextAtSize(firma, textSizeFirma);
            // const textHeight = customFont.heightAtSize(textSizeName);


            // if (
            // category == 'badge_empty_wystawca_a6' || 
            // category == 'home_wystawca_bathroom_empty_a6' ||
            // category == 'home_wystawca_furniture_empty_a6' ||
            // category == 'home_wystawca_kitchen_empty_a6' ||
            // category == 'home_wystawca_light_empty_a6' ||
            // category == 'home_wystawca_textile_empty_a6' ||
            // category == 'home_wystawca_build_empty_a6' ||

            // category == 'badge_empty_vip_a6' ||
            // category == 'badge_empty_vipgold_a6' ||
            // category == 'badge_empty_media_a6' ||
            // category == 'badge_empty_kongres_a6'
            // ) {

            // firstPage.drawText(getname, {
            // x: firstPage.getWidth() / 2 - textWidth / 2,
            // y: 175,
            // size: textSizeName,
            // font: customFont,
            // color: rgb(0, 0, 0),
            // });
            // firstPage.drawText(firma, {
            // x:  firstPage.getWidth() / 2 - textWidth2 / 2,
            // y: 160,
            // size: textSizeFirma,
            // font: customFont,
            // color: rgb(0, 0, 0),
            // });

            firstPage.drawImage(qrCodeImage, {
            x: 130,
            y: 85,
            width: 80,
            height: 120,
            });

            // } else if (category == 'badge_empty_montaz_a6') {

            // firstPage.drawText(firma, {
            // x:  firstPage.getWidth() / 2 - textWidth2 / 1.3,
            // y: 90,
            // size: 17,
            // font: customFont,
            // color: rgb(0, 0, 0),
            // });
            // console.log("x= " + (firstPage.getWidth() / 2 - textWidth2 / 2));

            // } else {
            // firstPage.drawText(getname, {
            // x: firstPage.getWidth() / 2 - textWidth / 2,
            // y: 165,
            // size: textSizeName,
            // font: customFont,
            // color: rgb(1, 1, 1),
            // });
            // secondPage.drawText(getname, {
            // x: firstPage.getWidth() / 2 - textWidth / 2,
            // y: 165,
            // size: textSizeName,
            // font: customFont,
            // color: rgb(1, 1, 1),
            // });
            // // ---
            // firstPage.drawText(firma, {
            // x:  firstPage.getWidth() / 2 - textWidth2 / 2,
            // y: 150,
            // size: textSizeFirma,
            // font: customFont,
            // color: rgb(1, 1, 1),
            // });
            // secondPage.drawText(firma, {
            // x: firstPage.getWidth() / 2 - textWidth2 / 2,
            // y: 150,
            // size: textSizeFirma,
            // font: customFont,
            // color: rgb(1, 1, 1),
            // });
            // // ---
            // secondPage.drawImage(qrCodeImage, {
            // x: 175,
            // y: 10,
            // width: 120,
            // height: 120,
            // });

            // }
            const pdfBytes = await pdfDoc.save()

            download(pdfBytes, "invitation.pdf", "application/pdf");
            setTimeout(function(){
            //window.close();
            },1000);
        }


    </script>

    <!-- <div id="footer">Badge generator 1.4 (local) | Sergiusz Skrypnychenko | Support: <a href="https://app.slack.com/client/TP60ETPDG/D0256U8CJUA">Slack</a></div> -->

    </html>
