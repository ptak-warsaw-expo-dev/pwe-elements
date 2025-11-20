// Function showing mass vip invitation send
// Handles the display of a modal window upon clicking a button.
// The modal allows users to interact with file upload fields
// and other elements while hiding the page footer during its active state.
jQuery(document).ready(function($){
    // Base varibales
    let fileContent = "";
    let fileArray = "";
    let fileLabel = "";
    const input_logo = '';
    let filteredArray = [];
    const tableCont = [];

    let emailTrue = true;
    const modal = $(".modal__element");
    const closeBtn = modal.find(".btn-close");
    const pageLang = (send_data['lang'] == "pl_PL") ? 'pl' : 'en';
    // const phone_field = send_data.phone_field ? send_data.phone_field : false ?? false;
    const phone_field = false;
    const formId = send_data['custom_form'];
    const group_tag = $('.exhibitor-generator').data('group');

    console.log(group_tag);
    $('.patron').val(group_tag);
    $('.patron input').val(group_tag);
    $('.gform_footer').append($('.tabela-masowa'));

    console.log(formId);
    // Button "Wysyłka Zbiorcze" functionality
    // Show odal and hide footer
    $(".tabela-masowa").on("click",function(e){
        e.preventDefault();
        modal.show();
        $("footer").hide();
    });

    // $('.exhibitor-generator[data-group="gr1"]').find('.badge_name.gfield_visibility_visible').hide();
    // if($('.exhibitor-generator[data-group="gr2"] .badge_name.gfield_visibility_visible').length > 0){
    //     $('.mass_checkbox_label').show();
    // }

    // "X" click will hide modal and show footer
    closeBtn.on("click", function () {
        modal.hide();
        $("footer").show();
    });

    $("#exhibitors_selector__modal").on("change", function(){
        $(".company-error").remove();
        $(".select-error").remove();

        switch($(this).val()){
            case "Firma Zapraszająca":
                $('.company').hide();
                $(`.patron`).val(group_tag);
                break;
            case "Patron":
                $('.company').val("");
                $('.company').show()
                $(`.patron`).val("patron");
                break;
            default:
                $('.company').hide();
                $('.company').val($(this).val());
                $(`.patron`).val(group_tag);
        }
    });

    // Remove error message for company name input
    $("#mass-table, .company").on("click", function(){
        if($(this).next().hasClass("company-error")){
            $(this).next().remove();
        }
    });

    $("#mass-table, .company").on("click", function(){
        if($(this).next().hasClass("company-error")){
            $(this).next().remove();
        }
    });

    // Show info box for file size on mouseover
    // Hide info box for file size on mouseleave
    // $('.info-box-sign').on('mouseenter', function(){
    //     $('.file-size-info').show();
    // }).on('mouseleave', function(){
    //     $('.file-size-info').hide();
    // });

    // Function to decode uploaded file on change
    // Creating two select fields for name and email
    $("#fileUpload").on("change", function(event) {
        filteredArray = [];

        // Remove old data and errors from corespondde fields
        $('.file-selector').remove();
        $('.file-error').remove();
        $('.file-size-error').hide();

        // Get file data
        const file = event.target.files[0];

        // If no file was added display alert and stops function.
        if (!file) {
            alert("Nie wybrano pliku.");
            return;
        }

        // Check if file extension is allowed array.
        const allowedExtensions = ["csv", "xls", "xlsx"];
        const fileExtension = file.name.split(".").pop().toLowerCase();
        if (!allowedExtensions.includes(fileExtension)) {
            alert("Niewłaściwy typ pliku. Proszę wybrać plik CSV, XLS lub XLSX.");
            return;
        }

        // Create Spiner and block pointer events for waiting time.
        $(".modal__element").find('.inner').prepend("<div id='spinner' class='spinner'></div>");
        $(".modal__element").find('.inner').addClass("blocked");

        const reader = new FileReader();

        // Function to decode file to (CSV format) text variable.
        reader.onload = function(e) {
            // If file size is over 1.2MB stop function and display error message.
            if( file.size > 1400000) {
                $(".email-error").remove();
                $(".file-size-error").show();
                $("#spinner").remove();
                $(".modal__element").find('.inner').removeClass("blocked");
                return;
            }

            // If file is not CSV, decode it with xlsx.js librarym,
            // Otherwise copy data to variable.
            if(file.name.split(".").pop().toLowerCase() != "csv"){
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, { type: "array" });
                const firstSheetName = workbook.SheetNames[0];
                const worksheet = workbook.Sheets[firstSheetName];
                fileContent = XLSX.utils.sheet_to_csv(worksheet);
            } else {
                fileContent = e.target.result;
            }

            // Cleaning file from "\r",
            // This will eliminate carriage return for better handeling.
            fileContent = fileContent.replace(/\r/g, "");

            // Splits file to arrey by "\n" thats only on end of the row.
            fileArray = fileContent.split(/\n(?=(?:[^"]|"[^"]*")*$)/);

            // Spliting every fileArray elements to columns arrays.
            fileArray.forEach(function(element){
                if (element.trim() !== "" && !/^[,\s"]+$/.test(element)){
                    let newElement = element.split(/,(?=(?:[^"]|"[^"]*")*$)/);

                    newElement = newElement.map(function(elem){
                        elem = elem.replace(/\\\\/g, ``);
                        elem = elem.replace(/\\"/g, ``);
                        return elem;
                    });

                    filteredArray.push(newElement);
                }
            });

            // Creat Label Array.
            fileLabel = filteredArray[0];

            // Remove waiting time spiner.
            $("#spinner").remove();
            $(".modal__element").find('.inner').removeClass("blocked");

            // Create drop downs for email, name and phone,
            // Populate the drop downs with file labels.
            if(pageLang == "pl"){
                // if(phone_field){
                //     $(".file-uloader").after("<div class='file-selector'><label>Kolumna z numerami telefonów</label><select type='select' id='phone-column' name='phone-column' class='selectoret'></select></div>");
                // }
                $(".file-uloader").after("<div class='file-selector'><label>Kolumna z adresami e-mail</label><select type='select' id='email-column' name='email-column' class='selectoret'></select></div>");
                $(".file-uloader").after("<div class='file-selector'><label>Kolumna z imionami i nazwiskami</label><select type='select' id='name-column' name='name-column' class='selectoret'></select></div>");
                $(".selectoret").each(function(){
                    $(this).append("<option value=''>Wybierz</option>");
                });
            } else {
                // if(phone_field){
                //     $(".file-uloader").after("<div class='file-selector'><label>Phone numbers column</label><select type='select' id='phone-column' name='phone-column' class='selectoret'></select></div>");
                // }
                $(".file-uloader").after("<div class='file-selector'><label>Email address column</label><select type='select' id='email-column' name='email-column' class='selectoret'></select></div>");
                $(".file-uloader").after("<div class='file-selector'><label>Names column</label><select type='select' id='name-column' name='name-column' class='selectoret'></select></div>");

                $(".selectoret").each(function(){
                    $(this).append("<option value=''>Chose</option>");
                });
            }

            fileLabel.forEach(function(element) {
                $(".selectoret").each(function(){
                    if(element != ""){
                        $(this).append(`<option value="${element}">${element}</option>`);
                    }
                })
            });

            // Check if selected email column of the file contains proper emails addresses,
            // If more then 5 wrong emails was find blocking send button and ask to check chosen column.
            $("#email-column").on("change", function(){
                const chosenLabel = $(this).val();
                const chosenID = fileLabel.findIndex(label => label == chosenLabel );
                let chosenErrors = -1;
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                for (let i = 1; i < filteredArray.length; i++){
                    const rowArray = filteredArray[i];

                    if (chosenErrors > 5){
                        if (!$(".file-selector").next(".email-error").length) {
                            if(pageLang == "pl"){
                                $(".file-selector").has("#email-column").after("<p class='email-error error-color'>W wybranej kolumnie znajduje się więcej niż 5 błędnych maili proszę o poprawienie przed kontynuacją.");
                            } else {
                                $(".file-selector").has("#email-column").after("<p class='email-error error-color'>In the selected column, there are more then 5 wrong email, please please correct them before continue</p>");
                            }
                        }
                        emailTrue = false;
                        break ;
                    } else if (rowArray[chosenID].length < 5 || !emailPattern.test(rowArray[chosenID].trim())){
                        console.log('wrong email - ' + rowArray[chosenID]);
                        chosenErrors++;
                    } else {
                        emailTrue = true;
                    }
                };

                if(emailTrue){
                    $(".error-color").remove();
                }
            });
        };

        // Reading file content.
        if (fileExtension === "csv") {
            reader.readAsText(file);
        } else {
            reader.readAsArrayBuffer(file);
        }
    });

    // Button "Wyślij" functionality,
    // Check if all file data is prceded corectli,
    // Send file data for procesing to mass-vip.php.
    $(".wyslij").on("click",function(){
        if(!emailTrue){
            return;
        }

        let company_name = "";
        let emailColumn = "";
        let nameColumn = "";
        let phoneColumn = "";
        let fileTrue = false;
        const fileInput = $('#exhibitor_logo')[0];

        // Check if file uploded correctly.
        if ($("#fileUpload").val() != ""){
            fileTrue = true;
        } else if($(".file-error").length == 0 ){
            if(pageLang == "pl"){
                $("#fileUpload").after("<p class='file-error error-color'>Proszę zamieścić plik</p>");
            } else {
                $("#fileUpload").after("<p class='file-error error-color'>Please add an file</p>");
            }
        }

        // Check if anything is selected.
        if ($("#exhibitors_selector__modal").val() != "" && $("#exhibitors_selector__modal").val() != "Firma Zapraszająca (wybierz z listy)"){
            company_select = $("#exhibitors_selector__modal").val();
        } else if($(".select-error").length == 0 ){
            if(pageLang == "pl"){
                $("#exhibitors_selector__modal").after("<p class='select-error error-color'>Wybierz firmę</p>");
            } else {
                $("#exhibitors_selector__modal").after("<p class='select-error error-color'>Select Company Name</p>");
            }
        }

        // Check if company field is populated.
        if ($(".company").val() != ""){
            company_name = $(".company").val();
        } else if($(".company-error").length == 0 ){
            if(pageLang == "pl"){
                $(".company").after("<p class='company-error error-color'>Nazwa firmy jest wymagana</p>");
            } else {
                $(".company").after("<p class='company-error error-color'>Company Name is required</p>");
            }
        }

        // Check if email column is chosen.
        if ($("#email-column").length > 0 && $("#email-column").val() != ""){
            emailColumn = $("#email-column").val();
        } else if($(".email-column-error").length == 0 ){
            if(pageLang == "pl"){
                $(".file-selector").has("#email-column").after("<p class='email-column-error error-color'>Wybierz kolumne z emailami</p>");
            } else {
                $(".file-selector").has("#email-column").after("<p class='email-column-error error-color'>Email required</p>");
            }
        }

        // Check if name column is chosen.
        if ($("#name-column").length > 0 && $("#name-column").val() != ""){
            nameColumn = $("#name-column").val();
        } else if($(".name-column-error").length == 0 ){
            if(pageLang == "pl"){
                $(".file-selector").has("#name-column").after("<p class='name-column-error error-color'>Wybierz kolumne z danymi</p>");
            } else {
                $(".file-selector").has("#name-column").after("<p class='name-column-error error-color'>Names required</p>");
            }
        }

        // Check if phone column is chosen.
        if (phone_field &&  $("#phone-column").length > 0 && $("#phone-column").val() != ""){
            phoneColumn = $("#phone-column").val();
        }
        // else if($(".name-column-error").length == 0 ){
        //     if(pageLang == "pl"){
        //         $(".file-selector").has("#name-column").after("<p class='name-column-error error-color'>Wybierz kolumne z danymi</p>");
        //     } else {
        //         $(".file-selector").has("#name-column").after("<p class='name-column-error error-color'>Names required</p>");
        //     }
        // }

        // Check if any of needed variables is not empty.
        if(company_name == "" || emailColumn == "" || nameColumn == "" || fileTrue === false){
            return;
        }

        // Creating chosen columns idexes for file data.
        const namelIndex = fileLabel.indexOf(nameColumn);
        const emailIndex = fileLabel.indexOf(emailColumn);
        let phoneIndex = '';
        if(phoneColumn) {
            phoneIndex = fileLabel.indexOf(phoneColumn);
        }
        let emailErrors = 0;

        // Create special array for post
        const tableCont = filteredArray.reduce((acc, row) => {
            const rowArray = row;
            if (rowArray[emailIndex] && rowArray[emailIndex].length > 5 && emailErrors < 5) {
                if(phoneColumn) {
                    acc.push({ "name": rowArray[namelIndex], "email": rowArray[emailIndex], "phone": rowArray[phoneIndex]});
                } else {
                    acc.push({ "name": rowArray[namelIndex], "email": rowArray[emailIndex]});
                }
            } else if (emailErrors < 5) {
                emailErrors++;
            } else {
                emailTrue = true;
            }
            return acc;
        }, []);

        console.log(tableCont);


        // Sending data via POST for procesing to mass-vip.php
        // Check if tableCont is populated, has less then 5000 elements and there is less then 5 email errors
        if (tableCont.length > 0 && tableCont.length < 5000 && emailErrors < 5){
            $(".modal__element .inner").prepend("<div id=spinner class=spinner></div>");
            const formData = new FormData();
            formData.append('data', JSON.stringify(tableCont));
            formData.append('token', send_data['secret']);
            formData.append('lang', pageLang);
            formData.append('formId', formId);
            formData.append('company', $(".company").val());
            formData.append('exhibitor_name', $('#mass_exhibitor_badge').prop("checked") ? '1' : '0');
            formData.append('exhibitor_logo', $('#exhibitor_logo_img').prop("src") ?? '');
            formData.append('exhibitor_desc', $('.exhibitor_desc input').val() ?? '');
            formData.append('patron', $('.patron').val() ?? '');
            formData.append('exhibitor_stand', $('#exhibitor_stand').val() ?? '');

            const fileInput = $('#exhibitor_logo')[0];
            if (fileInput?.files.length > 0) {
                formData.append('input_logo', fileInput.files[0]);
            }

            // Send data via POST
            $.ajax({
                url: send_data['send_file'],
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    console.log(response);
                    const resdata = JSON.parse(response);
                    $(".modal__element .inner").children().each(function () {
                        $(this).not('.btn-close').remove();
                    });
                if (resdata == 'true'){
                    $(".modal__element .inner").append("<p style='color:green; font-weight: 600; width: 90%;'>Dziękujemy za skorzystanie z generatora zaproszeń. Państwa goście wkrótce otrzymają zaproszenia VIP.</p>");
                } else {
                    $(".modal__element .inner").append("<p style='color:red; font-weight: 600; width: 90%;'>Przepraszamy, wystąpił problem techniczny. Spróbuj ponownie później lub zgłoś problem mailowo</p>");
                }
                $("#spinner").remove();
                tableCont.splice(0, tableCont.length);
                $("#dataContainer").empty();
                }
            });
        } else {
            if(pageLang == "pl"){
                $(".wyslij").before("<p class='company-error error-color' style='font-weight:700;'>Przepraszamy, wystąpił problem techniczny. Spróbuj ponownie później lub zgłoś problem mailowo</p>");
            } else {
                $(".wyslij").before("<p class='company-error error-color' style='font-weight:700;'>Company Name is required</p>");
            }
        }
    });
});

// 23-10-20244
// var btnExhElements = document.querySelectorAll(".btn-exh");
// btnExhElements.forEach(function(btnExhElement) {
//     btnExhElement.addEventListener("click", function() {
//         var containerElements = document.querySelectorAll(".container");
//         var infoItemElements = document.querySelectorAll(".info-item");

//         containerElements.forEach(function(containerElement) {
//             containerElement.classList.toggle("log-in");
//         });

//         infoItemElements.forEach(function(infoItemElement) {
//             infoItemElement.classList.toggle("none");
//         });
//     });
// });

// if (document.querySelector("html").lang === "pl") {
//     const companyNameInput = document.querySelector(".pwe-exhibitor-worker-generator input[placeholder='FIRMA ZAPRASZAJĄCA']");
//     const companyEmailInput = document.querySelector(".pwe-exhibitor-worker-generator input[placeholder='E-MAIL OSOBY ZAPRASZANEJ']");
//     console.log(companyNameInput);
//     if (companyNameInput && companyEmailInput) {
//         companyNameInput.placeholder = "FIRMA";
//         companyEmailInput.placeholder = "E-MAIL";
//     }
// } else {
//     const companyNameInputEn = document.querySelector(".pwe-exhibitor-worker-generator input[placeholder='INVITING COMPANY']");
//     const companyEmailInputEn = document.querySelector(".pwe-exhibitor-worker-generator input[placeholder='E-MAIL OF THE INVITED PERSON']");
//     console.log(companyNameInputE);
//     if (companyNameInputEn && companyEmailInputEn) {
//         companyNameInputEn.placeholder = "COMPANY";
//         companyEmailInputEn.placeholder = "E-MAIL";
//     }
// }