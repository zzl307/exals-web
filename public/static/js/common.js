// 时间戳转换
function datetime(ts){
	if (ts <= 0)
		return "0000-00-00 00:00:00";

	var t = new Date(ts * 1000);
	var Y = t.getFullYear();
	var m = t.getMonth() + 1;
	var d = t.getDate();
	var H = t.getHours();
	var i = t.getMinutes();
	var s = t.getSeconds();
	return Y+(m<10?"-0":"-")+m+(d<10?"-0":"-")+d+(H<10?" 0":" ")+H+(i<10?":0":":")+i+(s<10?":0":":")+s;
}

$('body').on('hidden.bs.modal', '.modal', function () {
    $(this).removeData('bs.modal');
});