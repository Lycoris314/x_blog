$(()=>{
    $("form").on("submit",(e)=>{

        e.preventDefault();
        const parameter = $("form").serialize();

        $.ajax({
            url:"./system/system_login.php",
            type:"get",
            data:parameter,
            cache:false,

        }).done((data)=>{
            console.log(data);
            if(data=="success"){
                location="./main.html"
            }else{
                $("p").text("ユーザ名またはパスワードが間違っています。");
            }
        })
    })
})