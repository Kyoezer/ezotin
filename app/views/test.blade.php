<!DOCTYPE HTML>
<html>
    <head>
        <title>TEST</title>
    </head>
    <body>
        {{Form::open(array('url'=>'test','files'=>true))}}
            <input type="file" name="File"/>
            <input type="submit" value="Submit"/>
        </form>
    </body>
</html>