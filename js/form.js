import $ from "jquery"

function handleSubmit(event)
{
    const submitter = $(event.originalEvent.submitter)
    const form = $(event.currentTarget);
    const url = new URL(form.prop("action"));
    const formData = new FormData(form[0]);
    formData.append(submitter.prop("name"), submitter.prop("value"))
    const searchParams = new URLSearchParams(formData);

    const fetchOptions = {
    	method: form.prop("method"),
    };

    if (form.prop("method").toLowerCase() === 'post') {
    	if (form.prop("enctype") === 'multipart/form-data') {
    		fetchOptions.body = formData;
    	} else {
    		fetchOptions.body = searchParams;
    	}
    } else {
    	url.search = searchParams;
    }

    fetch(url, fetchOptions).then((data) => data.text()).then((data) => {
        window.history.replaceState({}, "form submit", url)
        document.open()
        document.write(data)
        document.close()
    });

    event.preventDefault();
    return false
}

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
    $("form").bind("submit", handleSubmit)
})
