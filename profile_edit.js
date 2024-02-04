onload = () => {

    document.querySelector("#upfile").onchange = () => {


        let reader = new FileReader();
        reader.readAsDataURL(document.querySelector("#upfile").files[0]);

        reader.onload = () => {
            $(".icon").attr("src", reader.result);

        };
    };

    $(".to_cfm").on("click", (e) => {

        $("main").css("display", "none");
        $(".cfm").css("display", "block");

        $(".free_name").text($("input[name='free_name']").val());

        $(".profile").text($("textarea").val());
    });


    $(".back").on("click", () => {

        $("main").css("display", "block");
        $(".cfm").css("display", "none");
    });

    
    
};