<?php
    include('db/config.php');
    include('includes/header.php');
    $sql1 =  "SELECT * FROM `product`";
?>
    <div class="container container-expand">
        <div class="row">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col" style="width: 15px;">No</th>
                        <th scope="col" style="width: 166px;">Product</th>
                        <th scope="col">Location</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1;  foreach($conn->query($sql1) as $row){ ?>
                    <tr>
                        <td><?= $i.'.' ?></td>
                        <td><?= $row['name'] ?><img src='<?= $row['image_url']?>' alt='<?= $row['name']?>'></td>
                        <td>
                            <?php 
                                $result = $conn->query("SELECT location FROM `product_location` WHERE product_id = ". $row['id'] ."");
                                if($result && $result->num_rows > 0){
                                    $location = $result->fetch_assoc()['location'];
                                }else{
                                    $location = null;
                                }           
                                // echo $location;                     
                                foreach ($conn->query("SELECT * FROM `location`") as $row1) {
                                    $arr = explode(',', $location);                                
                                    $isChecked = in_array($row1['name'], $arr); // Check if the location is in the array
                                ?>
                                    <input type="checkbox" id="chk<?= $i ?>" name="product<?= $i ?>[]" style="margin: 0px;" class="select-item" value="<?= $row['id'].','.$row1['name'] ?>" <?php echo $isChecked ? 'checked' : ''; ?>>
                                    <label for="chk<?= $i ?>"><?= $row1['name'] ?></label>
                                <?php
                                }
                                ?>
                        </td>
                    </tr>

                    <?php $i++;} ?>
                </tbody>
            </table>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function(){    
            var selectedIds = new Map();            
            $('.select-item').change(function(){
                var product_name = $(this).closest('tr').find('td:eq(1)').text();
                var groupName = $(this).attr('name');
                console.log(product_name);
                
                selectedIds = $('input[name="'+groupName+'"]:checked').map(function(){
                    return $(this).val();
                }).get();  

                console.log(selectedIds);
                
                ajax('controller/update_location.php', 'POST', {
                    'product_name': product_name,                    
                    'Ids' : selectedIds,                    
                }).then(function(data){
                    console.log(data);
                })

                
            }); 
            
         
        });
        
    </script>


<?php
    
    include('includes/footer.php');
?>