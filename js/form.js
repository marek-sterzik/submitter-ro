import $ from "jquery"

$(() => {
    $(".non-validated-action").click(function (ev){
        var el = $(this)
        while (el.length > 0 && el.prop("tagName").toLowerCase() != "form") {
            el = el.parent()
        }
        if (el.length == 0) {
            return
        }
        el.find("[required]").removeAttr("required")
    })
})