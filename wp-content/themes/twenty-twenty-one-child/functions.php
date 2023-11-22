<?php
/*
 * This is the child theme for Twenty Twenty-One theme, generated with Generate Child Theme plugin by catchthemes.
 *
 * (Please see https://developer.wordpress.org/themes/advanced-topics/child-themes/#how-to-create-a-child-theme)
 */
add_action( 'wp_enqueue_scripts', 'twenty_twenty_one_child_enqueue_styles' );
function twenty_twenty_one_child_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('parent-style')
    );
}
/**
 * Proper way to enqueue scripts and styles.
 */


add_action( 'wp_enqueue_scripts', 'menu_scripts' );
function menu_scripts() {

wp_enqueue_script(
    'customt',
    get_stylesheet_directory_uri() . '/custom.js',
    array( 'jquery' )
);
        }

// function create_custom_post_type() {
//     register_post_type('custom_orders',
//         array(
//             'labels' => array(
//                 'name' => 'Custom Orders',
//                 'singular_name' => 'Custom Order',
//             ),
//             'public' => true,
//             'has_archive' => true,
//             'rewrite' => array('slug' => 'custom-orders'),
//         )
//     );
// }
// add_action('init', 'create_custom_post_type');


// Manage Order
function custom_orders_admin_page() {
    add_menu_page('Manage Orders', 'Manage Orders', 'edit_posts', 'manage_orders', 'custom_orders_page_callback','dashicons-portfolio');
}
add_action('admin_menu', 'custom_orders_admin_page');


function custom_orders_page_callback() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'your_custom_form_data';
  $currentDateTime = date('d/m/y H:i:s');
    $results = $wpdb->get_results("SELECT * FROM `custom_checkout_formdata` ORDER BY user_id", ARRAY_A);

    echo '<div class="wrap">';
    echo '<h2>Manage Order</h2>';

    if (!empty($results)) {
        echo '<table class="wp-list-table widefat fixed striped">';
        echo '<thead><tr><th>User ID</th><th>Order Date</th><th>Name</th><th>Contact</th><th>Email</th><th>Address</th><th>Landmark</th><th>City</th><th>State</th><th>ZIP Code</th></tr></thead>';
        echo '<tbody>';

        foreach ($results as $row) {
            echo '<tr>';
            echo '<td>' . $row['user_id'] . '</td>';
            echo '<td>' . $currentDateTime . '</td>';
            echo '<td>' . $row['billingName'] . '</td>';
            echo '<td>' . $row['billingContact'] . '</td>';
            echo '<td>' . $row['billingEmail'] . '</td>';
            echo '<td>' . $row['billingAddress'] . '</td>';
            echo '<td>' . $row['billingHouseNo'] . '</td>';
            echo '<td>' . $row['billingCity'] . '</td>';
            echo '<td>' . $row['billingState'] . '</td>';
            echo '<td>' . $row['billingZip'] . '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    } else {
        echo 'No form data found.';
    }

    echo '</div>';
}

function Work_Gallery() {
    $labels = array(
        'name'               => _x( 'Work_Gallery', 'Work_Gallery', 'Work_Gallery' ),
        'singular_name'      => _x( 'Work_Gallery', 'Work_Gallery', 'Work_Gallery' ),
        'menu_name'          => _x( 'Work_Gallery', 'admin menu', 'Work_Gallery' ),
        'name_admin_bar'     => _x( 'Work_Gallery', 'add new on admin bar', 'Work_Gallery' ),
        'add_new'            => _x( 'Add New', 'Work_Gallery', 'your-text-domain' ),
        'add_new_item'       => __( 'Add New Work_Gallery', 'your-text-domain' ),
        'new_item'           => __( 'New Work_Gallery', 'your-text-domain' ),
        'edit_item'          => __( 'Edit Work_Gallery', 'your-text-domain' ),
        'view_item'          => __( 'View Work_Gallery', 'your-text-domain' ),
        'all_items'          => __( 'All Work_Gallery', 'your-text-domain' ),
        'search_items'       => __( 'Search Work_Gallery', 'your-text-domain' ),
        'not_found'          => __( 'No Work_Gallery found', 'your-text-domain' ),
        'not_found_in_trash' => __( 'No Work_Gallery found in Trash', 'your-text-domain' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'Work_Gallery' ), 
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
     
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail',  'custom-fields','excerpt', 'comments' ),
    );

    register_post_type( 'Work_Gallery', $args );
}
add_action( 'init', 'Work_Gallery' );




add_shortcode('show_Work_Gallery', 'work_gallery_shortcode');

function work_gallery_shortcode(){
   
$args = array(
    'post_type'      => 'Work_Gallery',
    'posts_per_page' => -1,
    'publish_status' => 'published',
);

$loop = new WP_Query($args);
?>
<div class="row">
    <?php
    $column_counter = 0;
    $columns_per_row = 3; // Set the number of columns per row
    while ($loop->have_posts()) : $loop->the_post();
        // Get ACF fields
        $post_id = get_the_ID();
        $front_img = get_field('work_front_img');
        $img_title = get_field('img_title');
        $img_collage = get_field('img_collage');
        $slides = get_field('slides');
        ?>
        <?php if ($front_img) : ?>
            <div class="column">
                <img src="<?php echo $front_img; ?>" onclick="openModal(); currentSlide(1)" class="hover-shadow cursor" />
                <p><?php echo $img_title; ?></p>
            </div>
            <?php
            $column_counter++;
            if ($column_counter === $columns_per_row) {
                echo '</div><div class="row">';
                $column_counter = 0;
            }
            ?>
        <?php endif; ?>
    <?php endwhile; ?>
</div>




<div id="myModal" class="modal">
 
  <div class="modal-content">

            <?php
            // Check if img_collage exists and has images
             if( have_rows('img_collage') ):
                $count = 1;
                while( have_rows('img_collage') ): the_row(); ?>
                    <div class="mySlides">
                        
                        <div class="numbertext"><?php echo $count; ?> / <?php echo count($img_collage); ?></div>
                         <span class="close cursor" onclick="closeModal()">&times;</span>
                       <?php $image = get_sub_field('repeat_img');?>
                          <img src="<?php echo $image;?>" />

                    </div>
                    <?php
                    $count++;
                endwhile;
            endif;
            wp_reset_postdata();
            ?>
    <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
    <a class="next" onclick="plusSlides(1)">&#10095;</a>

    
  </div>
</div>

	<?php
}



//Creating the shortcode to show the order
function custom_sidebar_menu() {
    ob_start(); ?>
    <div class="sidebar-order-section">
      <?php  $user = get_field("in_process");
 $user_data = get_user_meta(get_current_user_id());    
//echo $user_data['author_content'][0]; 
//print_r($user_data);
$first_name = $user_data['first_name'][0];
?>


<p>Welcome <?php echo $first_name.'!'; ?></p>

        <h2>Orders</h2>
        <div class="order-row-section">
        <ul>
            <li class="sidebar-menu-item active" data-section="Process"><a href="#">In Process</a></li>
            <li class="sidebar-menu-item" data-section="Completed">Completed</li>
            <li class="sidebar-menu-item" data-section="Canceled">Cancelled</li>
            <li class="sidebar-menu-item" data-section="payment">Payment</li>
        </ul>
        </div>
        <h2>User Info</h2>
        <div class="payment-row-section">
        <ul>
            <li class="sidebar-menu-item" data-section="profile-setting">Profile Setting</li>
        </ul>
        <!-- <ul>-->
        <!--    <li class="sidebar-menu-item" data-section="payment">Payment</li>-->
        <!--</ul>-->
         <ul>
            <li class="sidebar-menu-item" id="redirectbutton" data-section="billing">Billing & Shipping</li>
        </ul>

          <ul>
			  <li class="sideba-item"><a href="https://typographystudio.com/logout">Logout</a></li> <!-- Change this to "data-section" -->
        </ul>
        
        
    </div>
    </div>
    
    <div class="content-section" id="Process">
        <div class="order-details">
            <div class="order-description">
                <h3>Order Description</h3>
                <?php 
                $use_id = get_current_user_id();
$order = get_field('order_desc','user_'. $use_id);

?>

<?php if($order){?>

                <p><?php echo $order;?></p>
                
              <?php }else{?>
              
              <p>not found</p>
            <?php  } ?>  
                
                
                
            </div>
  <div class="order-status">
    <h3>Status</h3>
   

<?php
//print_r($user_data);


 $current_user = wp_get_current_user();
 //print_r($current_user);
$author_id = get_current_user_id();
$author_field = get_field('in_process','user_'. $author_id);


?>
    <?php if($author_field['status']){?>

                <p><?php echo $author_field['status']; ?></p>
                
              <?php }else{?>
              
              <p>not found</p>
            <?php  } ?>  
            
            
   
    
</div>

            <div class="expected-delivery">
                <h3>Expected Delivery</h3>
                    
                    
                     <?php if($author_field['expected_delivery']){?>

                <p><?php echo $author_field['expected_delivery']; ?></p>
                
              <?php }else{?>
              
              <p>not found</p>
            <?php  } ?>
           
            </div>
        </div>
        
        
      <?php
// Get the current user's ID
$current_user_id = get_current_user_id();
$repeater_field_values = get_field('process_update_files', 'user_' . $current_user_id);
//print_r($repeater_field_values);

?>
  <?php
// Get the current user's ID
$current_user_id = get_current_user_id();



// Get the repeater field values for the current user
$repeater_field_values = get_field('process_update_files', 'user_' . $current_user_id);

?>

        <div class="process-update-files">
            <h4>Process Update Files</h4>
            <div class="image-section-row">
            <?php 
            if ($repeater_field_values) {
    foreach ($repeater_field_values as $row) {?>
   
    <?php
        // Access the 'image' subfield within each row of the repeater
        $image = $row['image'];

        if (!empty($image)) {?>
         <div class="image"><?php
            echo '<img src="' . $image['url'] . '" alt="' . $image['alt'] . '" />';
            ?> </div><?php
        }
    }   ?>
    
   
    <?php
} else {
    // Handle the case when the repeater field is empty or not found
    echo 'not found.';
}

?>
    </div>        
            
            
            
        </div>    
            
        </div>
        <div class="content-section" id="Completed">
        
        
        <?php
      
$comp_id = get_current_user_id();
$complete_field = get_field('completed','user_'. $comp_id);
     //  print_r($complete_field) ;
        
        
        ?>
        
        <h3>Order Status</h3>
            
            <?php if($complete_field){?>

                <p><?php echo $complete_field; ?></p>
                
              <?php }else{?>
              
              <h6>not found</h6>
            <?php  } ?>  
            
             
            
      
        <!-- Completed content goes here -->
    </div>
    <div class="content-section" id="Canceled">
        
         <h3>Cancelled Status</h3>
        
            <?php if($complete_field){?>

                <p>1</p>
                
              <?php }else{?>
              
              <h6>not found</h6>
            <?php  } ?>  
            
        <!--<h3>Your Order has been canceled</h3>-->
        <!-- Canceled content goes here -->
    </div>
     
        
        
      
  <?php
// Get the current user's ID
$current_user_id = get_current_user_id();

// Get the repeater field values for the current user
$payment_field_values = get_field('payment_reciept', 'user_' . $current_user_id);
//print_r($payment_field_values);

?>
        
        
        
        
        <div class="content-section" id="payment">
            <h3>Payment Reciept</h3>
            <div class="image-section-row">
            <?php 
            if ($payment_field_values) {
    foreach ($payment_field_values as $roww) {?>
   
    <?php
        // Access the 'image' subfield within each row of the repeater
        $img = $roww['reciept_img'];

        if (!empty($img)) {?>
         <div class="image"><?php
            echo '<img src="' . $img['url'] . '" alt="' . $img['alt'] . '" />';
            ?> </div> <?php
        }
    }   ?>
    
   
    <?php
} else {
    // Handle the case when the repeater field is empty or not found
    echo 'not found.';
}

?>
    </div>        
            
        
      
    </div>
    
    
 <div class="content-section" id="billing">
 

<?php
 
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['billing_form_submit'])) {
            //echo "helo";
        // Retrieve form data
        $billingName = sanitize_text_field($_POST['billingName']);
        $billingAddress = sanitize_text_field($_POST['billingAddress']);
        $billingState = sanitize_text_field($_POST['billingState']);
        $billingCity = sanitize_text_field($_POST['billingCity']);
        $billingContact = sanitize_text_field($_POST['billingContact']);
        $billingZip = sanitize_text_field($_POST['billingZip']);
        $billingEmail = sanitize_email($_POST['billingEmail']);
        $billingHouseNo = sanitize_text_field($_POST['billingHouseNo']);
//echo $billingName;
 global $wpdb;
$table_name = $wpdb->prefix . 'custom_checkout_formdata';
$current_user = wp_get_current_user();
//      // $user = get_user_by('email', 'user_email@example.com');
       $user_id = $current_user->ID;


$data_to_insert = array(
    'user_id' => $user_id ,
    'billingName' => $billingName,
    'billingContact' => $billingContact,
    'billingEmail' => $billingEmail,
    'billingAddress' => $billingAddress,
    'billingHouseNo' => $billingHouseNo,
    'billingCity' => $billingCity,
    'billingState' => $billingState,
    'billingZip' => $billingZip,
   
);

//print_r($data_to_insert);

 $rsult = $wpdb->insert('custom_checkout_formdata', $data_to_insert);
 //print_r($rsult);
  $data_saved = true;
  $form_class = 'hidden-form';

} 

}

 global $wpdb;
 $table_name = $wpdb->prefix . 'custom_checkout_formdata';
 
 $current_user = wp_get_current_user();
//      // $user = get_user_by('email', 'user_email@example.com');
       $user_id = $current_user->ID;


 $results = $wpdb->get_results("SELECT * FROM `custom_checkout_formdata` WHERE user_id = %d", ARRAY_A,$user_id );



//print_r($results);
foreach ($results as $data) {
    // Replace these with the actual field names and user IDs
    $user_id = $data['user_id'];  // Retrieve the user ID
    $billingName = $data['billingName'];
    $billingContact = $data['billingContact'];
    $billingEmail = $data['billingEmail'];
      $billingAddress = $data['billingCity'];

//print_r($user_id);

    // Update custom fields for the user
    update_user_meta($user_id, 'Billing_name', $billingName);
    update_user_meta($user_id, 'Billing_contact', $billingContact);
    update_user_meta($user_id, 'Billing_email', $billingEmail);
      update_user_meta($user_id, 'Billing_city', $billingAddress);
}

if (!($results)){ ?>
<style>
    /* Initially hide the form */
    .billing_form {
        display: none;
    }

    /* Style the label to look like a button */
    .accordion-button {
        background-color: #007bff;
        color: #fff;
        border: none;
        cursor: pointer;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 10px 0;
    }

    /* When the URL has a fragment identifier matching the form's ID, show the form */
    .billing_form:focus-within {
        display: block;
    }
</style>
<div class="new_custom_form">
      <div id="continueSavedAddress">
   <button id="continueButton">Continue with Saved Address</button>
</div>


<div id="addnewaddress">
    <button id="newaddbutton">+Add New Address</button>
</div>

</div>
<div id="savedAddressContainer" style="display: none">
    <?php
  
    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_checkout_formdata';
    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;

    $results = $wpdb->get_results(
        $wpdb->prepare("SELECT * FROM `custom_checkout_formdata` WHERE user_id = %d", $user_id),
        ARRAY_A
    );

    if (!empty($results)) {
        $data = $results[0]; // Assuming there is only one result for the current user
        $billingName = $data['billingName'];
        $billingContact = $data['billingContact'];
        $billingEmail = $data['billingEmail'];
        $billingaddress = $data['billingAddress'];
        $billinglandmark = $data['billingHouseNo'];
        $billingcity = $data['billingCity'];
        $billingstate = $data['billingState'];
        $billingZip = $data['billingZip'];

        // Display saved address details here
        ?>
        <p id="billingNameDetail"><?php echo 'Name: ' . $billingName; ?></p>
        <p id="billingContactDetail"><?php echo 'Contact No: ' . $billingContact; ?></p>
        <p id="billingEmailDetail"><?php echo 'Email: ' . $billingEmail; ?></p>
        <p id="billingAddressDetail"><?php echo 'Address: ' . $billingaddress; ?></p>
        <p id="billingHouseNoDetail"><?php echo 'Landmark: ' . $billinglandmark; ?></p>
        <p id="billingCityDetail"><?php echo 'City: ' . $billingcity; ?></p>
        <p id="billingStateDetail"><?php echo 'State: ' . $billingstate; ?></p>
        <p id="billingZipDetail"><?php echo 'Zip: ' . $billingZip; ?></p>
        <!-- Add more fields as needed -->
    
    <?php } else {
        echo "No saved address found for the current user.";
    }
    
   
    ?>
</div>

<?php

}

else{
    ?>
     <style>
        .hidden-form {
            display: block;
        }
        
            
    </style>
        <form name="myform" class="billing_form" method="post" data-saved-address="true">
       <div class ="billing_fields">
              <h3>Billing Information</h3>
               <label for="billingName">Name:</label><br>
      
        <input type="text" class="user_billing" id="billingName" name="billingName" required><br><br>
      
        
        <label for="billingContact">Contact Number:</label><br>
        <input type="tel" class="user_billing" id="billingContact" pattern="[7-9]{1}[0-9]{9}" name="billingContact" required><br><br>
        
        
         <label for="billingEmail">Email:</label><br>
        <input type="email" class="user_billing" pattern="/^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/" id="billingEmail" name="billingEmail" required><br><br>
        
          <input type="hidden" name="billing_form_submit" value="1">
        <label for="billingAddress">Address:</label><br>
        <input type="text" class="user_billing" id="billingAddress" name="billingAddress" required><br><br>
        
         <label for="home">Landmark:</label><br>
        <input type="text"class="user_billing" id="billingHouseNo" name="billingHouseNo" required><br><br>
         
          <label for="billingCity">City:</label><br>
        <input type="text" class="user_billing" id="billingCity" name="billingCity" required><br><br>

    
<label for="billingState">State:</label><br>
<input type="text" class="user_billing" id="billingState" name="billingState" required><br><br>

      
        <label for="billingZip">ZIP Code:</label><br>
        <input type="number" class="user_billing" id="billingZip" name="billingZip" required><br>
        <div class="checkbox-custom-section">
          <input type="checkbox" class="user_billing" id="sameAddressCheckbox" required ><br>
          <span class="same_field">Shipping Address is same as Billing Address</span>
          </div>
</div>

       <div class="shipping_fields">

        <h3>Shipping Address</h3>
      

        <label for="shippingName">Name:</label><br>
        <input type="text" class="user_billing" id="shippingName" name="shippingName" required><br><br>


        <label for="shippingContact">Contact:</label><br>
        <input type="number" class="user_billing" id="shippingContact" name="shippingContact" required><br><br>
        
        
        <label for="shippingEmail">Email:</label><br>
        <input type="email" class="user_billing"  id="shippingEmail" name="shippingEmail" required><br><br>
        
        <label for="shippingAddress">Address:</label><br>
        <input type="text" class="user_billing" id="shippingAddress" name="shippingAddress" required><br><br>
        
     <label for="shippingHouseNo">Landmark:</label><br>
        <input type="text" id="shippingHouseNo" name="shippingHouseNo" required><br><br>
        
          <label for="shippingCity">City:</label><br>
        <input type="text" class="user_billing" id="shippingCity" name="shippingCity" required><br><br>
        
        <label for="shippingState">State:</label><br>
        <input type="text" class="user_billing" id="shippingState" name="shippingState" required><br><br>

     
        <label for="shippingZip">ZIP Code:</label><br>
        <input type="number" class="user_billing" id="shippingZip" name="shippingZip" required><br><br>
   <input type="submit" value="Submit">
</div>
      

     
    </form>
   
  </div>
    
   
<?php }?>






<div id="add_newform" style="display:none">

<form name="myform" class="billing_form"  method="post" data-saved-address="true">
       <div class ="billing_fields">
              <h3><strong>Billing Address</strong></h3>
               <label for="billingName">Name:</label><br>
      
        <input type="text" class="user_billing" id="billingName" name="billingName" required><br><br>
      
        
        <label for="billingContact">Contact Number:</label><br>
        <input type="tel" class="user_billing" id="billingContact" pattern="[7-9]{1}[0-9]{9}" name="billingContact" required><br><br>
        
        
         <label for="billingEmail">Email:</label><br>
        <input type="email" class="user_billing" pattern="/^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/" id="billingEmail" name="billingEmail" required><br><br>
        
          <input type="hidden" name="billing_newform_submit" value="1">
        <label for="billingAddress">Address:</label><br>
        <input type="text" class="user_billing" id="billingAddress" name="billingAddress" required><br><br>
        
         <label for="home">Landmark:</label><br>
        <input type="text"class="user_billing" id="billingHouseNo" name="billingHouseNo" required><br><br>
         
          <label for="billingCity">City:</label><br>
        <input type="text" class="user_billing" id="billingCity" name="billingCity" required><br><br>

    
<label for="billingState">State:</label><br>
<input type="text" class="user_billing" id="billingState" name="billingState" required><br><br>

      
        <label for="billingZip">ZIP Code:</label><br>
        <input type="number" class="user_billing" id="billingZip" name="billingZip" required><br>
        <div class="checkbox-custom-section">
          <input type="checkbox" class="user_billing" id="sameAddressCheckbox" required ><br>
          <span class="same_field">Shipping Address is same as Billing Address</span>
          </div>
</div>

       <div class="shipping_fields">

        <h3><strong>Shipping Address</strong></h3>
      

        <label for="shippingName">Name:</label><br>
        <input type="text" class="user_billing" id="shippingName" name="shippingName" required><br><br>


        <label for="shippingContact">Contact:</label><br>
        <input type="number" class="user_billing" id="shippingContact" name="shippingContact" required><br><br>
        
        
        <label for="shippingEmail">Email:</label><br>
        <input type="email" class="user_billing"  id="shippingEmail" name="shippingEmail" required><br><br>
        
        <label for="shippingAddress">Address:</label><br>
        <input type="text" class="user_billing" id="shippingAddress" name="shippingAddress" required><br><br>
        
     <label for="shippingHouseNo">Landmark:</label><br>
        <input type="text" id="shippingHouseNo" name="shippingHouseNo" required><br><br>
        
          <label for="shippingCity">City:</label><br>
        <input type="text" class="user_billing" id="shippingCity" name="shippingCity" required><br><br>
        
        <label for="shippingState">State:</label><br>
        <input type="text" class="user_billing" id="shippingState" name="shippingState" required><br><br>

     
        <label for="shippingZip">ZIP Code:</label><br>
        <input type="number" class="user_billing" id="shippingZip" name="shippingZip" required><br><br>
   <input type="submit" value="Submit">
</div>
    </form>


</div>

</div>

    <div class="content-section" id="profile-setting">
        
        
                <h3>Profile</h3>
        <?php
    // Check if the user is logged in
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        
    ?>
    <div class="profile_setting">
    <p><strong>Username:</strong> <?php echo $current_user->user_login; ?></p>
    
    <h4>Biographical Info</h4>
    <p><?php echo get_the_author_meta('description', $current_user->ID); ?></p>
     
    <?php  
    
    $post_id = 2637; 
$page_url = get_permalink($post_id);
  
    ?>
    <div id="edit_user">
    <p><a href="<?php echo $page_url; ?>">Edit Profile</a></p>
    
    <!--<p><a href="<?ph// echo get_edit_profile_url($current_user->ID); ?>">Edit Profile</a></p>-->
  
    <?php } else { ?>
    
    <p>Please log in to view your profile.</p>
    
    <?php } ?>
    </div>
    </div>
    </div>
    
    <script>
        
        document.addEventListener('DOMContentLoaded', function () {
    const menuItems = document.querySelectorAll('.sidebar-menu-item');
    const contentSections = document.querySelectorAll('.content-section');

    // Initially hide Completed, Canceled, Profile, and Logout sections
    document.getElementById('Completed').style.display = 'none';
    document.getElementById('Canceled').style.display = 'none';
    document.getElementById('profile-setting').style.display = 'none';
    // document.getElementById('Logout').style.display = 'none';
    document.getElementById('payment').style.display = 'none';
     document.getElementById('billing').style.display = 'none';

    menuItems.forEach(function (menuItem) {
        menuItem.addEventListener('click', function () {
            const sectionId = menuItem.getAttribute('data-section');
            contentSections.forEach(function (section) {
                section.style.display = 'none';
            });
            const selectedSection = document.getElementById(sectionId);
            if (selectedSection) {
                selectedSection.style.display = 'block';
            }

            // Handle the Logout functionality
           jQuery("#redirectButton").on("click", function() {            // Ask for confirmation
           
                // If the user clicks OK, redirect to the desired URL
                window.location.href = "https://typographystudio.com"; // Replace with your desired URL
            
        });
        });
    });

jQuery("#continueButton").click(function(){
   
  savedAddressContainer.style.display = 'block';
});


//  const formdata = document.getElementById('savednewAddressContainer');
// jQuery("#continueButton").click(function(){
   
//   savednewAddressContainer.style.display = 'block';
// });



 const formdata = document.getElementById('add_newform');
jQuery("#newaddbutton").click(function(){
    
    formdata.style.display = 'block';
});


// ------------ Add new and continue with save address button js  End ----------------


    // Get the billing and shipping input fields
    const billingName = document.getElementById("billingName");
    const billingContact = document.getElementById("billingContact");
    const billingEmail = document.getElementById("billingEmail");
    const billingAddress = document.getElementById("billingAddress");
    const billingHouseNo = document.getElementById("billingHouseNo");
    const billingCity = document.getElementById("billingCity");
    const billingState = document.getElementById("billingState");
    const billingZip = document.getElementById("billingZip");

    const shippingName = document.getElementById("shippingName");
    const shippingContact = document.getElementById("shippingContact");
    const shippingEmail = document.getElementById("shippingEmail");
    const shippingAddress = document.getElementById("shippingAddress");
    const shippingHouseNo = document.getElementById("shippingHouseNo");
    const shippingCity = document.getElementById("shippingCity");
    const shippingState = document.getElementById("shippingState");
    const shippingZip = document.getElementById("shippingZip");

    const sameAddressCheckbox = document.getElementById("sameAddressCheckbox");

    // Add an event listener to the checkbox
    sameAddressCheckbox.addEventListener("change", function () {
        if (this.checked) {
            // Autofill shipping fields with billing information
            shippingName.value = billingName.value;
            shippingContact.value = billingContact.value;
            shippingEmail.value = billingEmail.value;
            shippingAddress.value = billingAddress.value;
            shippingHouseNo.value = billingHouseNo.value;
            shippingCity.value = billingCity.value;
            shippingState.value = billingState.value;
            shippingZip.value = billingZip.value;
        } else {
            // Clear the shipping fields
            shippingName.value = "";
            shippingContact.value = "";
            shippingEmail.value = "";
            shippingAddress.value = "";
            shippingHouseNo.value = "";
            shippingCity.value = "";
            shippingState.value = "";
            shippingZip.value = "";
        }
    });
});

//  add active class 
jQuery(document).ready(function () {
   jQuery(".sidebar-menu-item").click(function () {
        // Remove the active class from all menu items
        jQuery(".sidebar-menu-item").removeClass("active");

        // Add the active class to the clicked menu item
        jQuery(this).addClass("active");
    });
});
    </script>
    
    <?php
    return ob_get_clean();
}
add_shortcode('custom_sidebar_menu', 'custom_sidebar_menu');
