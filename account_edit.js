$(()=>{
    $("form").on("submit",(e)=>{
        e.preventDefault();
        const parameter = $("form").serialize();

        $.ajax({
            url:"./system/system_account_edit.php",
            type:"get",
            data:parameter,
            cache:false,

        }).done((data)=>{

            if(data=="success"){
                //let new_id_name=$("#new_id_name").val();
                //let new_password=$("#new_password").val();
                location="account_edit_cfm.php";
            }else{
                $("p").text("パスワードが間違っています。");
            }
        })
    })
})
