<div class="todaysTransaction">
    <?php
        include 'processes/db_connection.php';
        // session_start();
        // $username = $_SESSION['username'];
        date_default_timezone_set('Asia/Manila');
        $today = date('Y-m-d');
        $stmt = $conn->prepare("SELECT * FROM transactions WHERE (DATE(deadline) < ? AND transaction_status = 'not_loaded') OR DATE(deadline) = ?");
        $stmt->bind_param('ss', $today, $today);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($stmt->error) {
            echo $stmt->error;
        }

        if($result->num_rows > 0) {
            while($data = $result->fetch_assoc()){
                $availID = $data['avail_id'];
                $stmt1 = $conn->prepare("SELECT * FROM avails WHERE avail_id = ?");
                $stmt1->bind_param('s', $availID);
                $stmt1->execute();
                $result1 = $stmt1->get_result();

                if ($result1->num_rows > 0){
                    $data1 = $result1->fetch_assoc();
                    $name = $data1['availer_name'];
                    $username = $data1['added_by'];
                    $phoneNumber = $data1['phone_number'];
                    $transactionID = $data['transaction_id'];
                    $transactionStatus = $data['transaction_status'];
                    $timeAvailed = date("h:i A", strtotime($data1['time_availed']));

                    $dateEnds = $data1['date_ends'];
                    $daysLeft = round((strtotime($dateEnds) - strtotime($today))/(60*60*24));

                    if ($data['deadline'] == $today){
                        if($transactionStatus == 'not_loaded'){
                            if ($daysLeft == 1){
                                echo "<div class='showLoad'onClick='copyNumber($phoneNumber)'>
                                <div><span>$name ($phoneNumber)</span>
                                <a href='processes/updateTransaction.php?id=$transactionID'>DONE</a></div>
                                <div><small>$daysLeft day left</small> <small>$timeAvailed</small> <small>Added by $username</small></div>
                                </div>
                                ";
                            } elseif($daysLeft == 0) {
                                echo "<div class='showLoad'onClick='copyNumber($phoneNumber)'>
                                <div><span>$name ($phoneNumber)</span>
                                <a href='processes/updateTransaction.php?id=$transactionID'>DONE</a></div>
                                <div><small>Last day</small><small>$timeAvailed</small><small>Added by $username</small></div>
                                </div>
                                ";
                            } else {
                                echo "<div class='showLoad'onClick='copyNumber($phoneNumber)'>
                                <div><span>$name ($phoneNumber)</span>
                                <a href='processes/updateTransaction.php?id=$transactionID'>DONE</a></div>
                                <div><small>$daysLeft days left</small> <small>$timeAvailed</small><small>Added by $username</small></div>
                                </div>
                                ";
                            }
                        } else {
                            if ($daysLeft == 1){
                                echo "<div class='showLoad'onClick='copyNumber($phoneNumber)'>
                                <div><span>$name ($phoneNumber)</span><span>LOADED</span></div>
                                <div><small>$daysLeft day left</small> <small>$timeAvailed</small><small>Added by $username</small></div>
                                </div>
                                ";
                            } elseif($daysLeft == 0) {
                                echo "<div class='showLoad'onClick='copyNumber($phoneNumber)'>
                                <div><span>$name ($phoneNumber)</span><span>LOADED</span></div>
                                <div><small>Last day</small> <small>$timeAvailed</small><small>Added by $username</small></div>
                                </div>
                                ";
                            } else {
                                echo "<div class='showLoad'onClick='copyNumber($phoneNumber)'>
                                <div><span>$name ($phoneNumber)</span><span>LOADED</span></div>
                                <div><small>$daysLeft days left</small> <small>$timeAvailed</small><small>Added by $username</small></div>
                                </div>
                                ";
                            }
                        }
                    } else {
                        $daysPassed = round((strtotime($today) - strtotime($data['deadline']))/(60*60*24));

                        if ($daysPassed == 1){
                            echo "<div class='showLoad'onClick='copyNumber($phoneNumber)'>
                                <div><span>$name ($phoneNumber)</span>
                                <a href='processes/updateTransaction.php?id=$transactionID'>DONE</a></div>
                                <div><small>$daysPassed day passed</small><small>$timeAvailed</small> <small>Added by $username</small></div>
                                </div>
                                ";
                        } else {
                            echo "<div class='showLoad'onClick='copyNumber($phoneNumber)'>
                            <div><span>$name ($phoneNumber)</span>
                            <a href='processes/updateTransaction.php?id=$transactionID'>DONE</a></div>
                            <div><small>$daysPassed days passed</small><small>$timeAvailed</small> <small>Added by $username</small></div>
                            </div>
                            ";
                        }
                    }
                }
                
            }
        } else {
            echo "<i>No transactions today.</i>";
        }

    ?>
</div>

<script>
    function copyNumber(number){
        var tempInput =document.createElement('input');
        number = "0"+number;
            
        tempInput.setAttribute("value", number);
            
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand("copy");
        
            
        document.body.removeChild(tempInput);
        //alert("Number copied");
    }
</script>