<?PHP
	$path=$_SERVER['DOCUMENT_ROOT'];
	include $path.'/php/common.php';
?>

<!DOCTYPE HTML>
<html>
	<head>
		<?PHP getHtmlHead(); ?>

		<style>
			#screenRow > div{
				width: 19%;
				height: 150px;
				margin-bottom: 5px;
			}
			#screenRow {
				text-align: center;
			}
			.inline-block {
				display: inline-block;
			}
			.url-row {
				padding-top: 5px;
			}
			div > a.btn {
				padding-top: 0px;
				padding-bottom: 0px;
				border: 0px !important;
				vertical-align: middle !important;
			}
			.screen-ganglia {
				background-image: url('img/screen_placeholder.png');
			}
			.screen {
				border: 4px black solid;
				border-radius: 5px;
			}
			.schedule {
				background-color: #BDFFAE;
			}
			.override {
				background-color: #FFD4DD;
			}
		</style>
	</head>

	<body>
		<?PHP getHeader(); ?>
                <div id="pageContainer" class="container">
			<div class="panel panel-default">
				<div class="panel-heading">NOC Wall Displays</div>
				<div class="panel-body">
					<div id="screenRow" class="row">
						<div class="inline-block screen schedule"><div></div></div>
						<div class="inline-block screen schedule"><div></div></div>
						<div class="inline-block screen schedule"><div></div></div>
						<div class="inline-block screen schedule"><div></div></div>
						<div class="inline-block screen schedule"><div></div></div>
					</div>
					<div id="screenRow" class="row">
						<div class="inline-block screen schedule"><div></div></div>
						<div class="inline-block screen schedule"><div></div></div>
						<div class="inline-block screen schedule"><div></div></div>
						<div class="inline-block screen schedule"><div></div></div>
						<div class="inline-block screen schedule"><div></div></div>
					</div>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">NOC Wall Control</div>
				<div class="panel-body row">
					<div class="col-lg-12">
						<label>Run Override on <select><option></option><option>All</option><option>Inner Only</option><option>Outer Only</option></select></label>&nbsp;&nbsp;<a id="run-schedule-btn" class="btn">Run</a>
					</div>
					<div class="col-lg-12">
						<label>Restart Override on <select><option></option><option>All</option><option>Inner Only</option><option>Outer Only</option></select></label>&nbsp;&nbsp;<a id="restart-schedule-btn" class="btn">Restart</a>
					</div>
					<div class="col-lg-12">
						<label>Current URLs:</label>
					</div>
					
					<div class="col-lg-12">
						<div style="width:40px;" class="pull-left"><b>1:</b></div>
						<div class="col-lg-11"><input id="screen-1-url" type="text" style="width:100%;" disabled /></div>
						<div><a id="screen-1-url-btn" class="btn url-btn">Override</a></div>
					</div>
					<div class="col-lg-12">
						<div style="width:40px;" class="pull-left"><b>2:</b></div>
						<div class="col-lg-11"><input id="screen-2-url" type="text" style="width:100%;" disabled /></div>
						<div><a id="screen-2-url-btn" class="btn url-btn">Override</a></div>
					</div>
					<div class="col-lg-12">
						<div style="width:40px;" class="pull-left"><b>3:</b></div>
						<div class="col-lg-11"><input id="screen-3-url" type="text" style="width:100%;" disabled /></div>
						<div><a id="screen-3-url-btn" class="btn url-btn">Override</a></div>
					</div>
					<div class="col-lg-12">
						<div style="width:40px;" class="pull-left"><b>4:</b></div>
						<div class="col-lg-11"><input id="screen-4-url" type="text" style="width:100%;" disabled /></div>
						<div><a id="screen-4-url-btn" class="btn url-btn">Override</a></div>
					</div>
					<div class="col-lg-12">
						<div style="width:40px;" class="pull-left"><b>5:</b></div>
						<div class="col-lg-11"><input id="screen-5-url" type="text" style="width:100%;" disabled /></div>
						<div><a id="screen-5-url-btn" class="btn url-btn">Override</a></div>
					</div>
					<div class="col-lg-12">
						<div style="width:40px;" class="pull-left"><b>6:</b></div>
						<div class="col-lg-11"><input id="screen-6-url" type="text" style="width:100%;" disabled /></div>
						<div><a id="screen-6-url-btn" class="btn url-btn">Override</a></div>
					</div>
					<div class="col-lg-12">
						<div style="width:40px;" class="pull-left"><b>7:</b></div>
						<div class="col-lg-11"><input id="screen-7-url" type="text" style="width:100%;" disabled /></div>
						<div><a id="screen-7-url-btn" class="btn url-btn">Override</a></div>
					</div>
					<div class="col-lg-12">
						<div style="width:40px;" class="pull-left"><b>8:</b></div>
						<div class="col-lg-11"><input id="screen-8-url" type="text" style="width:100%;" disabled /></div>
						<div><a id="screen-8-url-btn" class="btn url-btn">Override</a></div>
					</div>
					<div class="col-lg-12">
						<div style="width:40px;" class="pull-left"><b>9:</b></div>
						<div class="col-lg-11"><input id="screen-9-url" type="text" style="width:100%;" disabled /></div>
						<div><a id="screen-9-url-btn" class="btn url-btn">Override</a></div>
					</div>
					<div class="col-lg-12">
						<div style="width:40px;" class="pull-left"><b>10:</b></div>
						<div class="col-lg-11"><input id="screen-10-url" type="text" style="width:100%;" disabled /></div>
						<div><a id="screen-10-url-btn" class="btn url-btn">Override</a></div>
					</div>
				</div>
			</div>
                </div>

		<script>
                        $(document).ready(function () {
				$(document).attr('title', $(document).attr('title')+' - NOC Wall Controller');

				/*
				$(' #screen-1-url ').text('screen 1');
				$(' #screen-2-url ').text('screen 2');
				$(' #screen-3-url ').text('screen 3');
				$(' #screen-4-url ').text('screen 4');
				$(' #screen-5-url ').text('screen 5');
				$(' #screen-6-url ').text('screen 6');
				$(' #screen-7-url ').text('screen 7');
				$(' #screen-8-url ').text('screen 8');
				$(' #screen-9-url ').text('screen 9');
				$(' #screen-10-url ').text('screen 10');
				*/
	

				$(' .url-btn ').on('click',function(){
					var btnCalled = this.id;
					var urlCalled = btnCalled.replace("-btn","");
					$('#'+urlCalled).prop('disabled',false);
					$(' .url-btn ').addClass('disabled');
					$(this).removeClass('disabled');
					//$(this).enabled();
					if( $(this).text()=='Override' ){
						$(this).text('Save');
					}else if( $(this).text()=='Save' ){
						$(' .url-btn ').removeClass('disabled');
						$('#'+urlCalled).prop('disabled',true);
						$(this).text('Override');
					}
				});
                        
                                setInterval(function(){
                                
				}, 10000);
                        });
                </script>
		<?PHP addFooter(); ?>
	</body>
</html>
