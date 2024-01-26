$(()=>{
    $("#form").on("submit",(e)=>{
        e.preventDefault();
        const parameter = $("#form").serialize();
        console.log(parameter);
        $.ajax({
            url:"./system/system_account_edit.php",
            type:"get",
            data:parameter,
            cache:false,

        }).done((data)=>{

            if(data=="success"){
                //let new_id_name=$("#new_id_name").val();
                //let new_password=$("#new_password").val();
                //location="account_edit_cfm.php";
                $("main").css("display","none");
                $(".cfm").css("display","block");
                $(".id_name").text($("#new_id_name").val());
                console.log($("#password").val());
                $("#password2").val($("#password").val());
                $("#new_password2").val($("#new_password").val());
                $("#new_id_name2").val($("#new_id_name").val());


            }else{
                $("p.wrong").text("パスワードが間違っています。");
            }
        })
    })

    $(".back").on("click",()=>{
        $("main").css("display","block");
        $(".cfm").css("display","none");
    })
})
