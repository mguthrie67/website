function destruct_calc(form) {
    var disk, ru, rack, cost;

    if (form.dest_RU.value.length == 0) {
        ru = 0.0;
        form.dest_Drives.readOnly = true;
        form.dest_Drives.value="";
    }
    else {
        ru = eval(form.dest_RU.value);
        form.dest_Drives.readOnly = false;
    }
    if (form.dest_Drives.value.length == 0)
        disk = 0.0;
    else
        disk = eval(form.dest_Drives.value);

    if (form.dest_Racks.value.length == 0)
        rack = 0.0;
    else
        rack = eval(form.dest_Racks.value);

    cost=rack * 500.0 + disk * 10.0 + ru * 200.0

    cost = cost.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');

    form.dest_Cost.value= "$" + cost;
}