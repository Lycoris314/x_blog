$(() => {

    const ed = $(".follow").attr("data-ed");

    $(".follow").on("click", (e) => {
        e.preventDefault();

        let onoff = $(".follow").attr("data-onoff");

        let msg;
        if(onoff=="on"){
            msg="フォローを解除してよろしいですか?"
        }else if(onoff=="off"){
            msg="フォローしてよろしいですか?"
        }
        
        if(confirm(msg)){
        
        $.ajax({
            type: "get",
            data: { ed: ed, onoff: onoff },
            url: "system/follow_unfollow.php",
            cache: false,
        }).done((data) => {

            if (data == "success") {
                
                if (onoff == "off") {
                    $(".follow").text("フォロー解除");
                    $('.follow').attr("data-onoff", "on");
                }
                if (onoff == "on") {
                    $(".follow").text("フォローする");
                    $('.follow').attr("data-onoff", "off");
                }

            }
        }).fail(function(jqXHR,textStatus,errorThrown){
            console.log(textStatus+" "+errorThrown);
        });
        }
    });

})