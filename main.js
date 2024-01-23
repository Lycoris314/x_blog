$(()=>{
    $("#tweet").on("submit",(e)=>{
        
        e.preventDefault();
        const parameter = $("form").serialize();
        $.ajax({
            url:"system/tweet.php",
            type:"post",
            data:parameter,
            cache:false,
        }).done((data)=>{
            if(data=="success"){
                $("textarea").val("");
                $("#tweeted").text("送信されました");
            }else{
                console.log(data);
                $("#tweeted").text(data);
            }
        })
    })
})