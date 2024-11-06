<?php 
    include("navbar.php");


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Reports</title>
</head>
<body>
    <div class="content_container">
        <div class="row">
        <div class="col-lg-12">
            <h2>Supplier Reports</h2><hr>
                
                <div class="row">
                    <div class="col-lg-4">
                    <select name="slct_supplier" id="slct_supplier" class="form-control" style="margin:5px;">
                            <option value="">Select Supplier...</option>
                            <?php
                                $result = $conn->query("select * from suppliers");

                                while($row=$result->fetch_assoc()){
                                    echo "<option value='$row[id]'>$row[name]</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-lg-4">
                    <select name="slct_status" id="slct_status" class="form-control" style="margin:5px;">
                            <option value="">Select Status...</option>
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>

                        </select>
                    </div>
                    <div class="col-lg-4">
                    <input type="date" name="txt_date" id="txt_date" class="form-control" style="margin:5px;">
                    </div>
                </div>
                <br>
                <button type="button" id="btn_generate" class="btn btn-info">Generate</button>
                <button type="button" class="btn btn-info" onclick="print()"><i class="bi bi-printer-fill"></i> &nbsp; Print</button>
                <br><hr><br>
                <div id="output" class="record_table">

                </div>
            </div>
        </div>
    </div>
</body>
<script>
    $(function(){
        LoadSupply();

        $("#btn_generate").click(function(){
            LoadSupply();
        });
    });

    function LoadSupply(){

        cParam = "";
        cParam = "supplier_id="+$("#slct_supplier").val();
        cParam += "&status="+$("#slct_status").val();
        cParam += "&order_date="+$("#txt_date").val();

        $.ajax({
            "type": "GET",
            "url": "load_supply.php",
            "data": cParam,
            "success": function(text){
                $("#output").html(text);

            }
        });
    }

    function print(){
        var supplier_id=$("#slct_supplier").val();
        var status= $("#slct_status").val();
        var order_date= $("#txt_date").val();

        var cFile = "print_supplies.php?supplier_id="+supplier_id+"&status="+status+"&order_date="+order_date;
            window.open(cFile, "_blank");
    }
</script>
</html>