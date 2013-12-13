function doPost(form, action, name){
    name = name || "submit";//defualt setting.
    var submitType = document.createElement("input");
    submitType.setAttribute("name", name);
    submitType.setAttribute("type", "hidden");
    submitType.setAttribute("value", "1");//”»’è—p‚Ì’l
    form.appendChild(submitType);
    form.action = action;
    form.method = "post";
    form.submit();
    return false;
}