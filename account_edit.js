$(() => {

    const new_pass = document.querySelector("#new_password");  
    const cfm_pass = document.querySelector("#cfm_password");
    
    new_pass.addEventListener("change", onChange); 
    cfm_pass.addEventListener("change", onChange); 

    const reg = /^[a-zA-Z0-9]{4,12}$/;

    function onChange() {

        if (reg.test(cfm_pass.value) && new_pass.value != cfm_pass.value) {
            cfm_pass.setCustomValidity("パスワードが確認用と異なっています。");
        } else {
            cfm_pass.setCustomValidity("");
        }
    }

    $("#form").on("submit", (e) => {
        e.preventDefault();
        const parameter = $("#form").serialize();

        $.ajax({
            url: "./system/system_account_edit.php",
            type: "get",
            data: parameter,
            cache: false,

        }).done((data) => {

            if (data == "success") {

                $("main").css("display", "none");
                $(".cfm").css("display", "block");
                $(".id_name").text($("#new_id_name").val());

                $("#password2").val($("#password").val());
                $("#new_password2").val($("#new_password").val());
                $("#new_id_name2").val($("#new_id_name").val());


            } else if(data =="wrong password"){
                $("p.wrong").text("パスワードが間違っています。");

            } else if(data=="duplicate"){
                $("p.wrong").text("このユーザ名は既に使われいます");
            }
        })
    })

    $(".back").on("click", () => {
        $("main").css("display", "block");
        $(".cfm").css("display", "none");
    })
})
