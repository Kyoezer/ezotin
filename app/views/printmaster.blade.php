<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Construction Development Board</title>
        <link rel="shortcut icon" href="{{asset('img/favicon.png')}}">
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <style>
			body {
			  font-family: sans-serif;
			  margin:0;
			  padding:0;
			}
			/*-------Print CSS---------*/
			.hr{
			    display: block;
			    height: 0;
			    overflow: hidden;
			    font-size: 0;
			    border-top: 1px solid #e3e3e3;
			    margin: 12px 0;
			  }
			h2.company-name{
			  font-family: 'Source Sans Pro', sans-serif;
			  margin:0;
			  padding:0;
			}
			p.heading{
			  margin:0;
			  padding:10px 0 5px 0;
			  font-weight: bold;
			  font-size: 11pt;
			}
			.printtitle{
			    margin:0;
			    padding:0 0 5px 0;
			    font-weight: bold;
			    font-size: 12pt;
			    text-decoration: underline;
			}
			.text-right {  
			  text-align: right;
			}
			.text-left {  
			  text-align: left;
			}
			.text-center{
			  text-align: center;
			}
			.x-x-small{
			  width:5%;
			}
			.x-small{
			  width:10%;
			}
			.small-s{
			  width:15%;
			}
			.small{
			  width:20%;
			}
			.small-medium{
			  width:30%; 
			}
			.x-small-medium{
			  width:25%; 
			}
			.medium{
			  width:50%; 
			}
			table.consultantservice td span{
				display:inline-block;
				width:12%;
			}
			table.consultantservice input[type="checkbox"]{
				vertical-align: left;
				margin:0;
			}
			table.data{
			  border-collapse:collapse;
			  width: 100%;
			  background: #fff;
			  font-size: 10pt;
			}
			table.data th{
			  background:#d7e5f2;
			  padding:3px 0;
			}
			table.data td{
			    font-size: 10pt;
			    padding:2px;
			    background:#fff;
			}
			table.data td.number{
			  text-align: right !important;
			}
			table.data tr {
			  background: #fff;
			}

			table.data tr:nth-child(odd) {
			  background: #ccc;
			}
			table.data-large{
			  border-collapse:collapse;
			  width: 100%;
			  background: #fff;
			  font-size: 10pt;
			}
			table.data-large th{
			  background:#d7e5f2;
			  border:1px solid #000;
			}
			table.data-large td{
			    font-size: 10pt;
			    border:1px solid #000;
			    padding:2px;
			    background:#fff;
			}
			table.data-large tr {
			  background: #fff;
			}

			table.data-large tr:nth-child(odd) {
			  background: #ccc;
			}
			table.data-small-with-border{
			  border-collapse:collapse;
			  width: 50%;
			  background: #fff;
			  font-size: 10pt;
			}
			table.data-small-with-border th{
			  background:#d7e5f2;
			  border:1px solid #000;
			}
			table.data-small-with-border td{
			    font-size: 10pt;
			    border:1px solid #000;
			    padding:2px;
			    background:#fff;
			}
			table.data-small-with-border tr {
			  background: #fff;
			}

			table.data-small-with-border tr:nth-child(odd) {
			  background: #ccc;
			}
			table.data-small{
			  border-collapse:collapse;
			  width: 50%;
			  background: #fff;
			  font-size: 10pt;
			}
			table.data-small th{
			  background:#d7e5f2;
			  padding:3px 0;
			}
			table.data-small td{
			    font-size: 10pt;
			    padding:2px;
			    background:#fff;
			}
			table.data-small tr {
			  background: #fff;
			}

			table.data-small tr:nth-child(odd) {
			  background: #ccc;
			}
			.bold-row{
			  font-weight: bold;
			}
			p.description{
			  font-size: 14px;
			}
        </style>
	</head>
	<body>
		<table class="data">
			<tbody>
				<tr>
					<td class="text-center">
						<h2 class="company-name">Construction Development Board</h2>
					</td>
				</tr>
			</tbody>
		</table>
		<p class="printtitle text-center">{{$printTitle}}</p>
		@yield('content')<br/>
		<table class="data">
			<tbody>
				<tr>
					<td><small><i>The document was viewed/printed on {{date('d-M-Y G:i:s')}}</i></small><b> @if(Auth::check()){{' by '.Auth::user()->FullName}}@endif</b></td>
				</tr>
			</tbody>
		</table>
	</body>
</html>