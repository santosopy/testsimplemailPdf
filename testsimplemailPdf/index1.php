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
        /* display: flex; */
        width: 100%;
        max-width: 900px;
        margin: auto;
        margin-top: 5%;
    }
    #pdf{
        width: 100%;
        padding: 0 1rem;
    }
    .pdf_inner{
        /* display: flex;
        flex-direction: row-reverse;
        justify-content: space-between; */
        font-size: 14px;
    }
    ul{
        list-style: none;
    }
    .logo_afloat{
        text-align: center;
        padding-bottom: 2.5rem;
    }
    .form-group:not(:last-of-type){
        display: flex;
    }
    .form-group label{
        max-width: 200px;
        width: 100%;
    }
    .button_submit{
        display: flex;
        gap: 1rem;
        justify-content: center;
        padding-top: 2rem;
    }
    </style>
</head>
<body>
    <div class="pdf_form_inner">
        <div class="text-center" id="err"></div>
        <form method="POST" action="" id="appForm">
            <div class="form-group">
                <label>名前</label>
                <input class="form-control" type="text" name="sender_name" placeholder="Your Name"/>
            </div>
            <div class="form-group">
                <label>メールアドレス</label>
                <input class="form-control" type="email" name="sender_email" placeholder="Recipient's Email Address"/>
            </div>
            <div class="form-group">
                <label>職業</label>
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
                <label>性別</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-control" type="radio" name="gender" id="gender" value="male">
                        <label class="form-check-label" for="inlineRadio1">male</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-control" type="radio" name="gender" id="gender" value="female">
                        <label class="form-check-label" for="inlineRadio2">female</label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>写真</label>
                <textarea class="form-control" name="message" placeholder="Message"></textarea>
            </div>
            <div class="form-group">
                <label>メッセージ</label>
                <input class="form-control" type="file" name="attachment" placeholder="Attachment"/>
            </div>
            <div class="form-group button_submit">
                <input class="btn btn-primary" type="submit" id="confirmBtn" name="button2" value="確認する" />
                <input class="btn btn-primary" type="submit" id="backBtn" name="button3" value="戻る" style="display:none" />
                <input class="btn btn-primary" type="submit" id="submitBtn" name="button" value="送信する" style="display:none" />
            </div>           
        </form>
        <div id="pdf" style="display: none"></div>
        <div id="attachCode" style="display: none"></div>
    </div>
    <script>
        // radio button 
        let myGender = ""

        confirmBtn.onclick = (e)=>{
            e.preventDefault()

            // validation
            const formDataEntries = new FormData(appForm).entries(),
                { sender_name, sender_email, occupation, gender, message, attachment } = Object.fromEntries(formDataEntries)
            
            err.innerHTML = ""

            if( !sender_name ){
                err.innerHTML += "<p class='bg-danger text-white p-2'>名前入力されていません</p>"
                return false
            }
            if( !sender_email ){
                err.innerHTML += "<p class='bg-danger text-white p-2'>メールアドレス入力されていません</p>"
                return false
            }
            if ( !/[^\s@]+@[^\s@]+\.[^\s@]+/.test(sender_email) ){
                err.innerHTML += "<p class='bg-danger text-white p-2'>メールアドレス間違ってます</p>"
                return false
            }
            if( !occupation ){
                err.innerHTML += "<p class='bg-danger text-white p-2'>職業入力されていません</p>"
                return false
            }
            if( gender === undefined ){
                err.innerHTML += "<p class='bg-danger text-white p-2'>性別入力されていません</p>"
                return false
            }
            if( !message ){
                err.innerHTML += "<p class='bg-danger text-white p-2'>メッセージ入力されていません</p>"
                return false
            }
            if( !attachment.name ){
                err.innerHTML += "<p class='bg-danger text-white p-2'>写真入力されていません</p>"
                return false
            }

            // content prepare
            document.querySelectorAll("input[name=gender]").forEach(element => {
                if( element.checked === true ) myGender = element.value
            })
            pdf.innerHTML = `
                <div class="pdf_inner">
                    <div class="logo_afloat">
                        <a href="https://school-afloat.com/">
                            <img src="./img/mylogo.png">
                        </a>
                        <h4 class="fw-bold text-center pt-3">入学願書のお申込み</h4>
                    </div>
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th width="200">名前</th>
                                <td>
                                    ${document.querySelector("input[name=sender_name]").value}
                                </td>
                            </tr>
                            <tr>
                                <th>メールアドレス</th>
                                <td>
                                    ${document.querySelector("input[name=sender_email]").value}
                                </td>
                            </tr>
                            <tr>
                                <th>職業</th>
                                <td>
                                    ${document.querySelector("select[name=occupation]").value}
                                </td>
                            </tr>
                            <tr>
                                <th>性別</th>
                                <td>
                                    ${myGender}
                                </td>
                            </tr>
                            <tr>
                                <th>メッセージ</th>
                                <td>
                                    ${document.querySelector("textarea[name=message]").value.replace(/\r?\n/g, '<br />')}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            `

            // image combine
            const myFile = document.querySelector("input[name=attachment]").files[0],
                reader = new FileReader()

            if( myFile !== undefined ){
                reader.readAsDataURL(myFile)
                reader.onload = function () {
                    attachCode.innerHTML = reader.result.split(',')[1]
                }
                reader.onerror = function (error) {
                    console.log('Error: ', error)
                }
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
                    scale: 4,
                    y: 0,
                    x: 0,
                    scrollY: 0,
                    scrollX: 0,
                    windowWidth: 800,
                },
                filename:     'myfile.pdf',
                image: { type: 'jpeg', quality: 1 },
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
                formData.append(
                    'image', 
                    attachCode.innerHTML
                )
                formData.append(
                    'imageType', 
                    document.querySelector("input[name=attachment]").files[0].type
                )
                formData.append(
                    'imageName', 
                    document.querySelector("input[name=attachment]").files[0].name
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

            // setTimeout(() => {
            //     pdf.style.display = "none"
            //     document.querySelector('form').style.display = "block"
            //     document.querySelector('form').reset()
            //     document.querySelectorAll('.form-control').forEach( e => e.style.cssText = `pointer-events: visible; background: transparent;` )
            // }, 1000)
        }
    </script>
</body>
</html>