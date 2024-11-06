<?php 
    include("navbar.php");


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
</head>
<body>
    <div class="content_container">

        <div class="row">
        <div class="col-lg-12">
            <h2>Sales Report</h2><br>
            
                <button type="button" class="btn btn-info" onclick="print1()" style="margin: 5px;"><i class="bi bi-printer-fill"></i> &nbsp;Print Product Sales</button>
                <button type="button" class="btn btn-info" onclick="print2()" style="margin: 5px;"><i class="bi bi-printer-fill"></i> &nbsp;Generate All Reports</button>
                <button type="button" class="btn btn-info" id="btn_generatebydate" style="margin: 5px;"><i class="bi bi-printer-fill"></i> &nbsp;Generate Reports By Date</button>

                <br><hr><br>
                <div class="record_table">
                <table width="100%" class="table table-bordered">
                    <tr>
                        <th>Product</th>
                        <th>Variant Name</th>
                        <th>Price Per Unit</th>
                        <th>Quantity</th>
                        <th>Total Amount</th>
                    </tr>
                    <?php
                        $result = $conn->query("SELECT 
                        p.name AS product_name,
                        pv.variant_name,
                        pv.price AS price_per_unit,
                        COALESCE(SUM(so.quantity), 0) AS total_quantity,
                        COALESCE(SUM(so.quantity * pv.price), 0) AS total_amount
                    FROM 
                        product_variants pv
                    JOIN 
                        products p ON pv.product_id = p.id
                    LEFT JOIN 
                        sales_orders so ON pv.id = so.product_variant_id AND so.status = 'delivered'
                    GROUP BY 
                        p.name, pv.variant_name
                    ORDER BY 
                        total_amount DESC;
                    ");

                    while($row=$result->fetch_assoc()){
                        echo "<tr>
                            <td>$row[product_name]</td>
                            <td>$row[variant_name]</td>
                            <td>₱ $row[price_per_unit]</td>
                            <td>$row[total_quantity]</td>
                            <td>₱ $row[total_amount]</td>
                        </tr>";
                    }
                    
                    ?>
                </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="slct_date_modal" style="display: none">
        <div id="slct_date_div">
            <h2><span class="btn_closemodal"><i class="bi bi-x-lg"></i></span> &nbsp;&nbsp;Generate Sales Report By Date</h2><hr>
                Start Date: <br><br>
                <input type="date" name="start_date" id="start_date" class="form-control"> <br>
                End Date: <br><br>
                <input type="date" name="end_date" id="end_date" class="form-control"> <br><br>
                <center><button type="button" class="btn btn-info" onclick="print3()">Generate</button></center>
        </div>

    </div>
</body>
<script>
    $(function(){
        $(".btn_closemodal").click(function(){
            $("#slct_date_modal").css("display","none");
            
        });

        $("#btn_generatebydate").click(function(){
            $("#slct_date_modal").css("display","flex");
            
        });
    });
    function print1(){
        var cFile = "print_productsales.php";
        window.open(cFile, "_blank");
    }

    function print2(){
        var cFile = "print_salesreport.php";
        window.open(cFile, "_blank");
    }

    function print3(){
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();

        if(start_date == "" || end_date == ""){
            alert("Please enter date for both.");
        }else{
            var cFile = "print_salesreportbydate.php?start_date="+start_date+"&end_date="+end_date;
            window.open(cFile, "_blank");
        }
    }

</script>
</html>