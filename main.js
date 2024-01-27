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
                console.log(data);
                $("textarea").val("");
                $("#tweeted").text("送信されました");
            }else{
                $("#tweeted").text(data);
            }
        })
    })
})