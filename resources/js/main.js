$(document).ready( function() {
    
    $(".open_modal").click( function(event) {
        alert("hellow!");
    });

    //exporte les données sélectionnées
    var $table = $('#table');
    $(function () {
        $('#toolbar').find('select').change(function () {
            $table.bootstrapTable('refreshOptions', {
                exportDataType: $(this).val()
            });
        });
    })

		var trBoldBlue = $("table");

	$(trBoldBlue).on("click", "tr", function (){
			$(this).toggleClass("bold-blue");
    });
    
    $("#type").change(function() {
        let selectedValue = $(this).val();
        let divForShow = `.answer-${ selectedValue }`;

        $("#type option").each( function() {
            let option = $(this).val();
            let optionForHide = `.answer-${ option }`;
            $( optionForHide ).hide();
        });

        $( divForShow ).show();
    });

    var inputFilePath = document.getElementById('filepath');

    if (inputFilePath !== null) {
        inputFilePath.addEventListener('change', showFileName);
    }

    function showFileName( event ) {
        let labelFile = document.getElementById('label-file');

        let input = event.srcElement;
        let fileName = input.files[0].name;
        labelFile.innerHTML = "Загружен файл: " + fileName;
    }

});