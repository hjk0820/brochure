<?php
    if(C('LAYOUT_ON')) {
        echo '{__NOLAYOUT__}';
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>跳转提示</title>
<style type="text/css">
*{ padding: 0; margin: 0; }
body{ background: #fff; font-family: '微软雅黑'; color: #333; font-size: 16px; }
.system-message{ padding:1px; width:400px; margin: 50px auto 0 auto; border:2px solid #B0BEC5; overflow: hidden;}
.system-message .title{ background: #B0BEC5; padding: 10px; font-weight: bold; color: #FFF;}
.system-message .img{ float: left; width: 32px; display: block; padding: 20px 0px 40px 18px;}
.system-message .success,
.system-message .error{ float: left; line-height: 1.8em; font-size: 1.5em; display: block; padding: 15px 18px; width: 314px;}
.system-message .detail{clear: both;}
.system-message .jump{ margin-top: 10px; display: block; float: left; text-align: right; background: #EFEFEF; padding: 5px 10px; width: 380px;}
.system-message .jump a{ color: #333;}
.system-message .detail{ font-size: 12px; line-height: 20px; margin-top: 12px; display:none;}
</style>
</head>
<body>
	<?php
	$txt  = '<div class="system-message">';
	$txt .= '<div class="title">跳转提示</div>';
	if(isset($message)){
		$txt .= '<p class="img"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAACKFBMVEUAAACwvsWLw0qwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWLw0qLw0qLw0qwvsWwvsWwvsWwvsWwvsWwvsWLw0qLw0qLw0qLw0qLw0qwvsWwvsWwvsWLw0qLw0qwvsWwvsWwvsWLw0qLw0qwvsWLw0qLw0qLw0qLw0qLw0qLw0qLw0qLw0qLw0qLw0qLw0qLw0qLw0qwvsWLw0qLw0qLw0qLw0qwvsWwvsWLw0qLw0qLw0qLw0qwvsWLw0qLw0qLw0qLw0qwvsWLw0qLw0qwvsWwvsWLw0qLw0qwvsWwvsWwvsWwvsWLw0qLw0qLw0qwvsWLw0qLw0qLw0qwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWLw0oAAAB7b5KqAAAAtXRSTlMAAAACHFmfwOT4++nIqGwmBQEqhNX3/fbglTgEEHTTomJBP1jJ6o8eKLH6vlwXAw9NrPU8MG8KVdLdSc49KbjeQLAdts914z4lzJsGK9/8SvDzS70RFzAWl64L1P5dOsjvwSfrOhjVaQi1kPxewwINCP6hEQd5sTb9ohQuRfLTOB/hUvk3OegPp9rbxxmr+acarM2eJ06q+5+rE1RRgr8SOVY04l6O0ROSN/Izce+YB5wt7DvERs3pZQAAAAFiS0dEAIgFHUgAAAAJcEhZcwAAAHgAAAB4AJ31WmAAAAJaSURBVDjLbZNpQxJhEIBnXkuFDDRbDqFUwCQjNTxSETOx0gyNbomyoks76T5VsrSSNLO7zErKo8Ou2d/X7oLAgvNpd55n5r0BooFpS5amZ2QqlMuylqvU2QjywJwVuSs50ig4rUJDSp0+zyBT0LhKR8rV+QWFJpPJrLdoqWhNMcYUROtaZck62/pSjAxWVr7BTlkVCwZipYUyq4zxCsDqjTVUWxfNoNVCjnqUj4nOBtrUKCXRuJkclZg0bcQmFzWrUfzaotm6LZmL+ZZW2t6GgDvc7VWpXDQ6dnp2CUIu7d4j54zt3befMcw+QJ1eaDroOyRvwNjhriNHjzHAPL//OJi5mhPJ/CTP86cE4XQ39UA65adhKj9zVhBQRecggwpEzs5fuMhYjAcuXRZ+sJDzQKaiUBDYlatd164zFuU3bt5i4kJu+wkUnEkU7tzl+d4+OQfsDxIotZJwb0AAvfcfSP2jHLBYEAYjHYYeCoR/9DihXhCcgwTDPrMosNCTAC9FnAPW20dglPTSPrGnY5IRiHNAm+8ZqDQWo7QPLDQmrwc0jNI4PHdzLyICC718NfA6zoUp6DzlYNDTm+rIVrK3794PJXCcoA+TgB+LtJ8WO27Az1PKsHDcpV/oq3MRA6dd1F0mXqn+YWpoSjHQO0PBWZTuXEUtuVqSDJyeKZkLR5KIdW5q7WhLMDDnm0sz990QexiNzfTj5+z8QoHBOjFFwbAh4Wmpf82R36Vq/N1f7Ky3jepoxDUrGxTnOzr/kI/zB4OD9nbyZITLUl6K92/PPw8JMeIYL5+M4f+KMrTSIBzQgQAAACV0RVh0ZGF0ZTpjcmVhdGUAMjAxNS0xMC0wOVQyMjoyNjozMiswODowMIEIG9IAAAAldEVYdGRhdGU6bW9kaWZ5ADIwMTUtMTAtMDlUMjI6MjY6MzIrMDg6MDDwVaNuAAAATnRFWHRzb2Z0d2FyZQBJbWFnZU1hZ2ljayA2LjguOC0xMCBRMTYgeDg2XzY0IDIwMTUtMDctMTkgaHR0cDovL3d3dy5pbWFnZW1hZ2ljay5vcmcFDJw1AAAAY3RFWHRzdmc6Y29tbWVudAAgR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxNi4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICByC3WWAAAAGHRFWHRUaHVtYjo6RG9jdW1lbnQ6OlBhZ2VzADGn/7svAAAAGHRFWHRUaHVtYjo6SW1hZ2U6OkhlaWdodAAyMTP9xXWSAAAAF3RFWHRUaHVtYjo6SW1hZ2U6OldpZHRoADIxM240Jc8AAAAZdEVYdFRodW1iOjpNaW1ldHlwZQBpbWFnZS9wbmc/slZOAAAAF3RFWHRUaHVtYjo6TVRpbWUAMTQ0NDQwMDc5Mv3u8/oAAAATdEVYdFRodW1iOjpTaXplADcuMTRLQkKlswtsAAAAWnRFWHRUaHVtYjo6VVJJAGZpbGU6Ly8vaG9tZS93d3dyb290L3d3dy5lYXN5aWNvbi5uZXQvY2RuLWltZy5lYXN5aWNvbi5jbi9zcmMvMTE5NDgvMTE5NDgzNy5wbmdbZGtCAAAAAElFTkSuQmCC"></p>';
		$txt .= '<p class="success">'.$message.'</p>';
	}else{
		$txt .= '<p class="img"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAACJVBMVEUAAACwvsX0QzawvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsX0Qzb0Qzb0Qzb0Qzb0Qzb0Qzb0Qzb0Qzb0Qzb0QzawvsWwvsWwvsWwvsWwvsWwvsX0Qzb0Qzb0Qzb0Qzb0Qzb0QzawvsWwvsWwvsWwvsWwvsWwvsX0Qzb0Qzb0Qzb0QzawvsWwvsWwvsX0Qzb0Qzb0Qzb0Qzb0QzawvsWwvsWwvsX0Qzb0Qzb0QzawvsX0Qzb0QzawvsX0Qzb0QzawvsWwvsX0Qzb0Qzb0Qzb0QzawvsWwvsX0Qzb0QzawvsWwvsWwvsX0QzawvsWwvsWwvsX0Qzb0QzawvsX0QzawvsWwvsWwvsWwvsWwvsX0Qzb0Qzb0QzawvsWwvsWwvsWwvsWwvsX0QzawvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsWwvsX0QzYAAADH7B0pAAAAtHRSTlMAAAACHFmfwOT4++nIqGwmBQEqhNX3/fbglTgEEHTTomJBP1jJ6o8eKLH6vlwXAw9NrPU8MG8KVdLdSc49KbjeQLAdts914z4lzJsGK9/8CluTTQQBOo5sEUrw80u9Effwa+H9h5euC9T+XZLxvgjrOhjvA+L8fAi1kFCVDsPz7S71lB/h7vpzAujH+/n5p81dnidOjbyrnYK/EjlWwb0hNOJejtEFE5I38jNx75gHnC3sO8SpQQDLAAAAAWJLR0QAiAUdSAAAAAlwSFlzAAAAeAAAAHgAnfVaYAAAApNJREFUOMtlk/k/lFEUxu+5CjNpkN5ZzBQzRiQhW7bRZFQkkiU0LbZoSqaaCC2KKY0i2kirqSwt2s77/3Xv+74zZrg/3c95vvee85xzLyHKgogtWyOjolXqbTHbNbFxQMIXxO9I2CmgViXoVFpU6w2JxjAETLv0qN6dlGy2WCwpBqsOU/ekQRABSN+rztiXuT8L5GTZOQdyMSYvQADkWzG6wLR+gkDhwSIsLlEikG7F0jIIzwm2cjxkl4JgOoyl+bChbIAKB1bGAt8d0R49tlHn8apqPF4DBE7U1hUA0JP1pxoaKZdoU/PpllYK0HbGeZYBCXjuPFBaf0Fs7+AEpQ2dYld3E4W4i9jTSyouuS6zC+gVURT7GCHponi1nxJIdLuvkRSh6DrwYzckwkMbbrLNwCBlwK0hHCaRmBTBKqSNHX0SMSLpt+9QXqcG75IoTJYcKMS90aDOCLPgJNEqs2yReiQiRCdw341EJViUHjDiAde7xhSdwLgXiVoXBOjIQw48mngcANIY4AvcQOX6FS8yYPMhmXSlQIj+5KnSDwkoy50i02jgg1D6MzA20bdOQKbrGdForSYONHfK9cte2md4o4zTOEue1woveCdfKv6UfrzirbbpnTnEaMDXhWwWLV2Kf5mY87BxzuObBQJvU3Xv2LRau98Pyv5p48yHuY/sgk+Laj8bd9Zn/GJjhKc/YJ829X9l+pIDh7L5kxqfxPKKTU8KepfRuwLSm8srRkfVBgKWljNW/XIQoKQWq9tqQgiI/+bQrn43Bj+GvRJ//FxZCxwwps8votdvDPlasb9W0e3Q2H+Pp9nKMqf1OOVYCUsKa209f9AluL1eX24dOqP82Zt+Su/f4X9OZGuqdDZnISj/B7hT3pAJiPJBAAAAJXRFWHRkYXRlOmNyZWF0ZQAyMDE1LTEwLTA5VDIyOjIxOjM5KzA4OjAwYdNUUQAAACV0RVh0ZGF0ZTptb2RpZnkAMjAxNS0xMC0wOVQyMjoyMTozOSswODowMBCO7O0AAABOdEVYdHNvZnR3YXJlAEltYWdlTWFnaWNrIDYuOC44LTEwIFExNiB4ODZfNjQgMjAxNS0wNy0xOSBodHRwOi8vd3d3LmltYWdlbWFnaWNrLm9yZwUMnDUAAABjdEVYdHN2Zzpjb21tZW50ACBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDE2LjAuMCwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIHILdZYAAAAYdEVYdFRodW1iOjpEb2N1bWVudDo6UGFnZXMAMaf/uy8AAAAYdEVYdFRodW1iOjpJbWFnZTo6SGVpZ2h0ADIxM/3FdZIAAAAXdEVYdFRodW1iOjpJbWFnZTo6V2lkdGgAMjEzbjQlzwAAABl0RVh0VGh1bWI6Ok1pbWV0eXBlAGltYWdlL3BuZz+yVk4AAAAXdEVYdFRodW1iOjpNVGltZQAxNDQ0NDAwNDk5aHqUKwAAABN0RVh0VGh1bWI6OlNpemUANy42NUtCQq8vsBkAAABadEVYdFRodW1iOjpVUkkAZmlsZTovLy9ob21lL3d3d3Jvb3Qvd3d3LmVhc3lpY29uLm5ldC9jZG4taW1nLmVhc3lpY29uLmNuL3NyYy8xMTk0Ny8xMTk0NzQxLnBuZxgZC4YAAAAASUVORK5CYII="></p>';
		$txt .= '<p class="error">'.$error.'</p>';
	}
	$txt .= '<p class="detail"></p>';
	$txt .= '<p class="jump">';
	$txt .= '	页面自动 <a id="href" href="'.$jumpUrl.'">跳转</a>';
	$txt .= '	等待时间：<b id="wait">'.$waitSecond.'</b>';
	$txt .= '</p>';
	$txt .= '</div>';
	echo $txt;
	?>
<script type="text/javascript">
(function(){
var wait = document.getElementById('wait'),href = document.getElementById('href').href;
var interval = setInterval(function(){
	var time = --wait.innerHTML;
	if(time <= 0) {
		location.href = href;
		clearInterval(interval);
	};
}, 1000);
})();
</script>
</body>
</html>
