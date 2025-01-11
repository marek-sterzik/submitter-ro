import $ from "jquery"

var element = null

function createElement()
{
    element = $("<div class=\"modal-foreground\"><div class=\"progress\"><span class=\"left\"><span class=\"progress-bar\"></span></span><span class=\"right\"><span class=\"progress-bar\"></span></span><div class=\"progress-value\">0%</div></div></div>")
    $("body").prepend(element)
}

function range(x, min, max)
{
    return Math.max(Math.min(x, max), min)
}

function transform(x, left)
{
    if (left) {
        x = range((x - 0.5) * 2, 0, 1)
    } else {
        x = range(x * 2, 0, 1)
    }
    const angle = Math.round(180 * x)
    return "rotate(" + angle + "deg)"
}

function showModalProgress(value)
{
    if (value !== null & value !== undefined) {
        if (element === null) {
            createElement()
        }
        element.show()
        if (value !== false) {
            element.find(".progress").show()
            const percent = range(Math.round(value * 100), 0, 100)
            element.find(".progress-value").text(percent + " %")
            console.log("left", transform(value, true))
            console.log("right", transform(value, false))
            element.find(".left .progress-bar").css("transform", transform(value, true))
            element.find(".right .progress-bar").css("transform", transform(value, false))
        } else {
            element.find(".progress").hide()
        }
    } else {
        if (element !== null) {
            element.hide()
        }
    }
}

export default showModalProgress
