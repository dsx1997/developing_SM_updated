<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
    });

    function deleteSupervisor(formId, myObj) {

        var div1 = myObj.parentNode.parentNode;

        let form1 = new FormData();
        form1.append("formId", formId);

        var ajaxOptions = {
            url: '/classes/delete_supervisor',
            type: 'POST',
            cache: false,
            processData: false,
            dataType: 'json',
            contentType: false,
            data: form1,
        };

        var req = $.ajax(ajaxOptions);

        req.done(function(resp) {

            let all_teachers = resp.all_teachers;
            let div2 = '';
            for (let i = 0; i < all_teachers.length; i++) {
                div2 += `<option value="` + all_teachers[i].id + `">` + all_teachers[i].name + `</option>`;
            }
            div1.innerHTML = `<select required data-placeholder="Assign" class="form-control " onchange="assignSupervisor(` + formId + `, this)" data-id="` + formId + `">
                                                     <option value="">Assign</option>
                                                     ` + div2 + `
                                                  </select>`;
            resp.ok && resp.msg ?
                flash({
                    msg: resp.msg,
                    type: 'success'
                }) :
                flash({
                    msg: resp.msg,
                    type: 'danger'
                });
            hideAjaxAlert();
            return resp;
        });
        req.fail(function(e) {
            if (e.status == 422) {
                var errors = e.responseJSON.errors;
                displayAjaxErr(errors);
            }
            if (e.status == 500) {
                displayAjaxErr([e.status + ' ' + e.statusText + ' Please Check for Duplicate entry or Contact School Administrator/IT Personnel'])
            }
            if (e.status == 404) {
                displayAjaxErr([e.status + ' ' + e.statusText + ' - Requested Resource or Record Not Found'])
            }
            return e.status;
        });
    }

    function assignSupervisor(formId, myObj) {

        let td1 = myObj.parentNode;
        var teacher_id = myObj.options[myObj.selectedIndex].value;

        let form1 = new FormData();
        form1.append("formId", formId);
        form1.append("teacher_id", teacher_id);
        var ajaxOptions = {
            url: '/classes/assign_supervisor',
            type: 'POST',
            cache: false,
            processData: false,
            dataType: 'json',
            contentType: false,
            data: form1,
        };

        var req = $.ajax(ajaxOptions);

        req.done(function(resp) {

            let teacher_name = resp.teacher_name;
            td1.innerHTML = `<div class="d-flex align-items-center justify-content-between">
                                <p style="margin: 0;">` + teacher_name + `</p>
                                <button class="btn" style="background:transparent;line-height: 7px;margin:0;font-size: 10px;height:auto" title="Delete this user" onclick="deleteSupervisor(` + formId + `, this);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="color:red" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
  <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
  <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
</svg>
                                </button>
                            </div>`;
            resp.ok && resp.msg ?
                flash({
                    msg: resp.msg,
                    type: 'success'
                }) :
                flash({
                    msg: resp.msg,
                    type: 'danger'
                });
            hideAjaxAlert();
            return resp;
        });
        req.fail(function(e) {
            if (e.status == 422) {
                var errors = e.responseJSON.errors;
                displayAjaxErr(errors);
            }
            if (e.status == 500) {
                displayAjaxErr([e.status + ' ' + e.statusText + ' Please Check for Duplicate entry or Contact School Administrator/IT Personnel'])
            }
            if (e.status == 404) {
                displayAjaxErr([e.status + ' ' + e.statusText + ' - Requested Resource or Record Not Found'])
            }
            return e.status;
        });
    }

    function showMyClass() {
        // alert('ok');
        // history.go('/classes');
    }

    function showRadio(myObj) {
        var detail = document.querySelector('#detail');
        var detailContent = document.querySelector('#detail-content');
        var detailPrint = document.querySelector('#detail-print');
        if (myObj.value == "Custom") {
            detail.classList.remove('active-state');
            detailContent.classList.add('active-state');
            detailPrint.classList.add('active-state');
        } else {
            detail.classList.add('active-state');
            detailContent.classList.remove('active-state');
            detailPrint.classList.remove('active-state');
        }
    }

    function showPrint() {
        $('.basic').addClass('active-state');
        $('.print').removeClass('active-state');
    }

    function showManageTeacher() {
        $('.basic').removeClass('active-state');
        $('.print').addClass('active-state');
    }
    $("#example-search-input").on("keyup", function() {
        var count = $('.teacher_count').text();
        var value = $(this).val().toLowerCase();
        for (let index = 0; index < 18; index++) {
            var label = $('#item' + index).attr('aria-label')
            console.log(label.toLowerCase(), label.toLowerCase().indexOf(value) > -1)
            if (label.toLowerCase().indexOf(value) < 0) {
                if (!$('#item' + index).hasClass('active-state')) {
                    $('#item' + index).addClass('active-state')
                }
                // console.log($('#item'+index).hasClass('active-state'))
            } else {
                if ($('#item' + index).hasClass('active-state')) {
                    $('#item' + index).removeClass('active-state')
                }
            }

        }
    });

    function phoneCheck() {
        if ($('#phone').is(":checked")) {
            $('#phone').prop('checked', true);
            $('.phone').removeClass('active-state');
        } else {
            $('#phone').prop('checked', false);
            $('.phone').addClass('active-state');
        }

    }

    function emailCheck() {
        if ($('#username').is(":checked")) {
            $('#username').prop('checked', true);
            $('.email').removeClass('active-state');
        } else {
            $('#username').prop('checked', false);
            $('.email').addClass('active-state');
        }

    }

    function nationCheck() {
        if ($('#national').is(":checked")) {
            $('#national').prop('checked', true);
            $('.nation').removeClass('active-state');
        } else {
            $('#national').prop('checked', false);
            $('.nation').addClass('active-state');
        }

    }

    function genderCheck() {
        if ($('#gender').is(":checked")) {
            $('#gender').prop('checked', true);
            $('.gender').removeClass('active-state');
        } else {
            $('#gender').prop('checked', false);
            $('.gender').addClass('active-state');
        }

    }

    function tscCheck() {
        if ($('#tsc').is(":checked")) {
            $('#tsc').prop('checked', true);
            $('.tsc').removeClass('active-state');
        } else {
            $('#tsc').prop('checked', false);
            $('.tsc').addClass('active-state');
        }

    }

    function groupCheck() {
        if ($('#group').is(":checked")) {
            $('#group').prop('checked', true);
            $('.group').removeClass('active-state');
        } else {
            $('#group').prop('checked', false);
            $('.group').addClass('active-state');
        }

    }

    function fnExcelReport() {
        var tab_text = document.getElementById('printView').innerHTML;
        window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
        window.focus();
    }

    function fnPrintReport(e) {
        e.preventDefault();
        var mywindow = window.open('', 'PRINT', 'height=800,width=1024');
        mywindow.document.write('<html><head><title>' + " " + '</title>');
        // mywindow.document.write('<html><head><title>' + document.title  + '</title>');
        mywindow.document.write('</head><body >');
        // mywindow.document.write('<h1>' + document.title  + '</h1>');
        mywindow.document.write(document.getElementById('printView').innerHTML);
        mywindow.document.write('</body></html>');

        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10*/

        mywindow.print();
        // mywindow.close();
        return true;
    }

    function checkListType() {
        if ($('#listType').hasClass('active-state')) {
            $('#listType').removeClass('active-state');
        } else {
            $('#listType').addClass('active-state');
        }

    }

    function checkClassType() {
        if ($('#classType').hasClass('active-state')) {
            $('#classType').removeClass('active-state');
        } else {
            $('#classType').addClass('active-state');
        }

    }
    $('#select_by_key').on('click', function() {
        if (!$('.select_file').hasClass('active-state')) {
            $('.select_file').addClass('active-state');
        }
        if ($('.select_key').hasClass('active-state')) {
            $('.select_key').removeClass('active-state');
        }
    });
    $('#select_by_file').on('click', function() {
        if (!$('.select_key').hasClass('active-state')) {
            $('.select_key').addClass('active-state');
        }
        if ($('.select_file').hasClass('active-state')) {
            $('.select_file').removeClass('active-state');
        }
    });

    function uploadFile_onChange(input, sort) {
        var files = document.getElementById('file_upload').files;
        if (files.length == 0) {
            alert("Please choose any file...");
            return;
        }
        var filename = files[0].name;
        var extension = filename.substring(filename.lastIndexOf(".")).toUpperCase();
        if (extension == '.XLS' || extension == '.XLSX') {
            //Here calling another method to read excel file into json
            excelFileToJSON(files[0], sort);
        } else {
            // alert("Please select a valid excel file.");
            $('#display_excel_data').removeClass('active-state');
            var table = document.getElementById("display_excel_data");
            var htmlData = '<tr><th>#</th><th>Name</th><th>Title</th><th>National Id No</th><th>Group</th></tr>';
            table.innerHTML = htmlData;
        }
    }

    function excelFileToJSON(file, sort) {
        try {
            var reader = new FileReader();
            reader.readAsBinaryString(file);
            reader.onload = function(e) {

                var data = e.target.result;
                var workbook = XLSX.read(data, {
                    type: 'binary'
                });
                var result = {};
                var firstSheetName = workbook.SheetNames[0];
                //reading only first sheet data
                var jsonData = XLSX.utils.sheet_to_json(workbook.Sheets[firstSheetName]);
                // alert(JSON.stringify(jsonData));
                //displaying the json result into HTML table
                displayJsonToHtmlTable(jsonData, sort);
            }
        } catch (e) {
            console.error(e);
        }
    }

    function displayJsonToHtmlTable(jsonData, sort) {
        $('#display_excel_data').removeClass('active-state');
        var table = document.getElementById("display_excel_data");
        if (jsonData.length > 0) {
            if (sort == 1) {
                var htmlData = '<tr><th>#</th><th>Name</th><th>Phone</th><th>TSC NO</th><th>National Id No</th><th>Gender</th><th>Group</th></tr>';

                for (var i = 0; i < jsonData.length; i++) {
                    var row = jsonData[i];
                    htmlData += '<tr><td>' + Number(i + 1) + '</td><td>' + row["NAME"] + '</td><td>' + row['PHONE'] + '</td><td>' + row['TSC_NO'] + '</td><td>' + row["NATIONAL_ID_NO"] + '</td><td>' + row['GENDER'] + '</td><td>' + row["GROUP"] + '</td></tr>';
                }
            } else {
                var htmlData = '<tr><th>#</th><th>Name</th><th>Title</th><th>National Id No</th><th>Group</th></tr>';
                for (var i = 0; i < jsonData.length; i++) {
                    var row = jsonData[i];
                    htmlData += '<tr><td>' + Number(i + 1) + '</td><td>' + row["NAME"] + '</td><td>' + row['TITLE'] + '</td><td>' + row["NATIONAL_ID_NO"] + '</td><td>' + row["GROUP"] + '</td></tr>';
                }
            }
            table.innerHTML = htmlData;
        } else {

            // table.innerHTML='There is no data in Excel';
            if (sort == 1) {
                var htmlData = '<tr><th>#</th><th>Name</th><th>Phone</th><th>TSC NO</th><th>National Id No</th><th>Gender</th><th>Group</th></tr>';
            } else {
                var htmlData = '<tr><th>#</th><th>Name</th><th>Title</th><th>National Id No</th><th>Group</th></tr>';
            }
            table.innerHTML = htmlData;
        }
    }
    var create_exam_submit_btn = document.querySelector('#create-staff-btn');
    $('#create_staff_form').on('submit', function(e) {
        e.preventDefault();
        let form1 = new FormData();
        form1.append("full_name", $('#full_name').val());
        form1.append("email", $('#email').val());
        form1.append("phone_number", $('#phone_number').val());
        form1.append("tsc_no", $('#tsc_no').val());
        form1.append("gender", $('#gender').val());
        form1.append("national_id_no", $('#national_id_no').val());
        form1.append("group", $('#group').val());
        var ajaxOptions = {
            url: $('#create_staff_form').attr('action'),
            type: 'POST',
            cache: false,
            processData: false,
            dataType: 'json',
            contentType: false,
            data: form1,
        };
        var req = $.ajax(ajaxOptions);
        req.done(function(resp) {
                console.log('======= residences ===============');
                console.log(resp);
                resp.ok && resp.msg ?
                    flash({
                        msg: resp.msg,
                        type: 'success'
                    }) :
                    flash({
                        msg: resp.msg,
                        type: 'danger'
                    });
                hideAjaxAlert();
                // enableBtn($(create_exam_submit_btn));
                // window.location.href="/exams";
                // return resp;
                $('#staff_name_helper').text('');
                $('#staff_email_helper').text('');

            })
            .fail(function(e) {
                if (e.status == 422) {
                    var errors = e.responseJSON.errors;
                    console.log(errors)
                    errors.forEach(error => {
                        if (error == "The full name field is required.") {
                            $('#staff_name_helper').text(error);
                        }
                        if (error == "The email field is required.") {
                            $('#staff_email_helper').text(error);
                        }
                    })
                }
                if (e.status == 500) {
                    displayAjaxErr([e.status + ' ' + e.statusText + ' Please Check for Duplicate entry or Contact School Administrator/IT Personnel'])
                }
                if (e.status == 404) {
                    displayAjaxErr([e.status + ' ' + e.statusText + ' - Requested Resource or Record Not Found'])
                }
                enableBtn($(create_exam_submit_btn));
            });
    });
</script>