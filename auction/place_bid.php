<?php

// TODO: Extract $_POST variables, check they're OK, and attempt to make a bid.
// Notify user of success/failure and redirect/give navigation options.

session_start();

if ($_SESSION['logged_in'] == true && $_SESSION['account_type']=='buyer')  // Check if the user has loggin in as a buyer
{

    $bid_price = $_POST['bid'];
    $buyer_id = $_SESSION['buyer_id'];  
    $item_id = $_SESSION['bided_item_id'];         
    $current_price = $_SESSION['bided_current_price'];                 // retreive necessary information for placing the bid

    if ($bid_price<=$current_price)
    {
        echo "Invalid price entered. Place bid unsuccessful.";
        header("refresh:2;url=browse.php");                      // check if the user input price is higher than current price
    }

    else
    {
        date_default_timezone_set("Europe/London");
        $bid_time = date("Y-m-d H:i:s");                               // get the time of placing bid (in London timezone)


        include_once 'opendb.php'; 
        $bid_query = "INSERT INTO bids (buyer_id, listing_id, bidtime, bidprice) VALUES ($buyer_id, $item_id, '$bid_time', $bid_price)";
        $bid_result = mysqli_query($connection,$bid_query) or die(" Insert into database unsuccessfull.");
                                                                    // insert the bid information to database
        if($bid_result)
        {
            echo "Bid placed successfully at: " . $bid_time;
			include_once 'send_bid_notif_buyer.php';
            header("refresh:2;url=browse.php");                  // notify the user of success bid placement and redirection
        }
        mysqli_close($connection);
    }
}

else
{
    echo "You are not logged in with a buyer account. Try again.";      // The user is not loggin in or not as a buyer
    header("refresh:2;url=browse.php");
}
?>