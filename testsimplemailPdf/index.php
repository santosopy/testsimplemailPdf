<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <title>Send Attachment With Email</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <style>
    .pdf_form_inner{
        display: flex;
        width: 100%;
        max-width: 900px;
        margin: auto;
        margin-top: 10%;
    }
    #pdf{
        width: 100%;
    }
    .pdf_inner{
        display: flex;
        flex-direction: row-reverse;
        justify-content: space-between;
        font-size: 14px;
    }
    ul{
        list-style: none;
    }
    </style>
</head>
<body>
    <div class="pdf_form_inner">
        <form method="POST" action="" style="width: 500px;">
            <div class="form-group">
                <input class="form-control" type="text" name="sender_name" placeholder="Your Name" required/>
            </div>
            <div class="form-group">
                <input class="form-control" type="email" name="sender_email" placeholder="Recipient's Email Address" required/>
            </div>
            <div class="form-group">
                <select class="form-control" name="occupation">
                    <option value="" selected="selected">【選択して下さい】</option>
                    <option value="高校生">高校生</option>
                    <option value="大学生">大学生</option>
                    <option value="短大生">短大生</option>
                    <option value="専門学校生">専門学校生</option>
                    <option value="パート・アルバイト">パート・アルバイト</option>
                    <option value="その他">その他</option>
                </select>
            </div>
            <div class="form-group">
                <div class="form-check form-check-inline">
                    <input class="form-control" type="radio" name="gender" id="gender" value="male">
                    <label class="form-check-label" for="inlineRadio1">male</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-control" type="radio" name="gender" id="gender" value="female">
                    <label class="form-check-label" for="inlineRadio2">female</label>
                </div>
            </div>
            <div class="form-group">
                <textarea class="form-control" name="message" placeholder="Message"></textarea>
            </div>
            <div class="form-group">
                <input class="form-control" type="file" name="attachment" placeholder="Attachment"/>
            </div>
            <div class="form-group">
                <input class="btn btn-primary" type="submit" id="submitBtn" name="button" value="Submit" style="display:none" />
                <input class="btn btn-primary" type="submit" id="confirmBtn" name="button2" value="Confirm" />
                <input class="btn btn-primary" type="submit" id="backBtn" name="button3" value="Back" style="display:none" />
            </div>           
        </form>
        <div id="pdf" style="display: none"></div>
    </div>
    <script>
        // radio button 
        let myGender = ""

        confirmBtn.onclick = (e)=>{
            e.preventDefault()

            // content prepare
            document.querySelectorAll("input[name=gender]").forEach(element => {
                if( element.checked === true ) myGender = element.value
            })
            pdf.innerHTML = `
                <div class="pdf_inner">
                    <ul>
                        <li>
                            <img src="" id="myImage" width="150">
                        </li>
                    </ul>
                    <ul>
                        <li>
                            名前 : 
                            ${document.querySelector("input[name=sender_name]").value}
                        </li>
                        <li>
                            メールアドレス : 
                            ${document.querySelector("input[name=sender_email]").value}
                        </li>
                        <li>
                            職業 : 
                            ${document.querySelector("select[name=occupation]").value}
                        </li>
                        <li>
                            性別 : 
                            ${myGender}
                        </li>
                        <li>
                            メッセージ : 
                            ${document.querySelector("textarea[name=message]").value.replace(/\r?\n/g, '<br />')}
                        </li>
                    </ul>
                </div>
            `

            // image combine
            const myFile = document.querySelector("input[name=attachment]").files[0],
                reader = new FileReader()
            reader.readAsDataURL(myFile)
            reader.onload = function () {
                myImage.setAttribute("src", reader.result)
            }
            reader.onerror = function (error) {
                console.log('Error: ', error)
            }
            
            document.querySelectorAll('.form-control').forEach( e => e.style.cssText = `pointer-events: none; background: gainsboro;` )
            confirmBtn.style.display = "none"
            submitBtn.style.display = "block"
            backBtn.style.display = "block"
        }
        backBtn.onclick = (e)=>{
            e.preventDefault()

            document.querySelectorAll('.form-control').forEach( e => e.style.cssText = `pointer-events: visible; background: transparent;` )
            confirmBtn.style.display = "block"
            submitBtn.style.display = "none"
            backBtn.style.display = "none"
        }
        submitBtn.onclick = (e)=>{
            e.preventDefault()

            // form remove
            document.querySelector('form').style.display = "none"
            pdf.style.display = "block"
            
            // pdf execute
            opt = {
                margin: [5,0,5,0],
                html2canvas:  { 
                    scale: 1.5,
                    y: 0,
                    x: 0,
                    scrollY: 0,
                    scrollX: 0,
                    windowWidth: 800,
                },
                filename:     'myfile.pdf',
            }

            // html2pdf().set(opt).from(pdf).save()
            html2pdf().set(opt).from(pdf).outputPdf().then(function(pdf) {
                const formData = new FormData()
                formData.append(
                    'sender_name', 
                    document.querySelector("input[name=sender_name]").value
                )
                formData.append(
                    'sender_email', 
                    document.querySelector("input[name=sender_email]").value
                )
                formData.append(
                    'occupation', 
                    document.querySelector("select[name=occupation]").value
                )
                formData.append(
                    'gender', 
                    myGender
                )
                formData.append(
                    'message', 
                    document.querySelector("textarea[name=message]").value
                )
                formData.append(
                    'pdf', 
                    btoa(pdf)
                )

                fetch(`${window.location.href}process.php`, {
                    method: 'post',
                    body: formData
                })
                .then(response => response.text())
                .then(body => {
                    console.log(body)
                })

            })

            setTimeout(() => {
                pdf.style.display = "none"
                document.querySelector('form').style.display = "block"
                document.querySelector('form').reset()
                document.querySelectorAll('.form-control').forEach( e => e.style.cssText = `pointer-events: visible; background: transparent;` )
            }, 1000)
        }
    </script>
</body>
</html>