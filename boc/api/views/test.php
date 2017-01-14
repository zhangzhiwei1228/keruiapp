<!DOCTYPE html>
<?php echo date('Y-m-d', strtotime('next Monday', '1456884158'));; ?>
<?php
// replace(/\=/g, '.').replace(/\+/g, '*').replace(/\//g, '-');
// $data = array(
//     'orderid' => 'BAPRO160310155531244743',
//     'products' => array(
//         array(
//             'id'=>2,
//             'content'=>'评价内容',
//             'photo'=>'2,3,4')
//     )
// );
// echo json_encode($data);
// echo '<br />';
$data = array(
            array(
                'id'=>18,
                'content'=>'评价内容，在今天',
                'photo'=>'2,3,4')
);
$data = json_encode($data);
// $data = base64_encode($data);
//
echo $data;
echo '<br />';

// // 订单评价格式
// $data = array(
//             array(
//                 'id'=>19,
//                 'content'=>'这里是评价内容',
//                 'photo'=>'2,3,4')
// );
// $data = json_encode($data);
// $data = base64_encode($data);

echo $data;
echo '<br />';
// $data = array(
//     'cartId'=>2,
//     'num'=>2,
//     'options'=>array(
//         2=>7,
//         3=>2
//     )
// );
// $data = json_encode($data);
// $data = base64_encode($data);

echo '<br />';
// 修改购物车
$data = array(
    'cartId'=>17,
    'num'=>2,
    'options'=>array(
        array(
            'option'=>'2',
            'optval'=>'7'
        ),
        array(
            'option'=>'3',
            'optval'=>'2'
        ),
    )
);
$data = json_encode($data);

echo $data;
echo '<br />';
$data = base64_encode($data);

echo $data;
echo '<br />';
echo preg_match('/^WATER[0-9]{3,}$/', 'WATER160309113631242005');

echo '<br />';
// 产品下单
$data = array(
    array(
        'sellerId' => 3,
        'cartIds' => 128,
        'addrId' => 1,
        'commnet' => '大订单'
    ),
    array(
        'sellerId' => 2,
        'cartIds' => 129,
        'addrId' => 1,
        'commnet' => '又一个大订单'
    )
);
$data = json_encode($data);
// $data = base64_encode($data);

echo $data;
echo '<br />';
ob_clean();
?>
<head>
<?php //include_once VIEWS.'inc/head.php'; ?>
<script src = "https://cdn.wilddog.com/js/client/current/wilddog.js" ></script>
</head>

<body>
    <a href=mailto:sample@163.com>send email</a>
    <?php include_once VIEWS.'inc/header.php'; ?>
    <a href="<?php echo site_url('activity_info'); ?> "></a>
    <?php include_once VIEWS.'inc/footer.php'; ?>
</body>
<script type="text/javascript">
var ref_test = new Wilddog("https://xqbumu.wilddogio.com/test");
// ref_test.set({
//     "name" : "Jack Bauer",
//     "age" : 32,
//     "location" : {
//         "city" : "beijing",
//         "zip" : 100000
//     }
// });
ref_test.child("location/city").on("value", function(datasnapshot) {
  console.log(datasnapshot.val());   // 结果会在 console 中打印出 "beijing"
});

// var ref = new Wilddog("https://docs-examples.wilddogio.com/web/saving-data/wildblog");
var ref = new Wilddog("https://xqbumu.wilddogio.com/web/saving-data/wildblog");

var usersRef = ref.child("users");
usersRef.set({
  alanisawesome: {
    date_of_birth: "June 23, 1912",
    full_name: "Alan Turing"
  },

  gracehop: {
    date_of_birth: "December 9, 1906",
    full_name: "Grace Hopper"
  }
});

var hopperRef = usersRef.child("gracehop");

hopperRef.update({
  "nickname": "Amazing Grace"
});

var postsRef = ref.child("posts");

postsRef.push({
  author: "gracehop",
  title: "Announcing COBOL, a New Programming Language"
});

postsRef.push({
  author: "alanisawesome",
  title: "The Turing Machine"
});

// 通过push()来获得一个新的数据库地址
var newPostRef = postsRef.push({
   author: "gracehop",
   title: "Announcing COBOL, a New Programming Language"
});

// 获取push()生成的唯一ID
var postID = newPostRef.key();

postsRef.on("child_added", function(snapshot) {
    var newPost = snapshot.val();
    console.log("Author: " + newPost.author);
    console.log("Title: " + newPost.title);
});


var ref_dinosaur = new Wilddog("https://xqbumu.wilddogio.com/dinosaur");
ref_dinosaur.set({
  "lambeosaurus": {
    "height" : 2.1,
    "length" : 12.5,
    "weight": 5000
  },
  "stegosaurus": {
    "height" : 4,
    "length" : 9,
    "weight" : 2500
  }
});
</script>
</html>
