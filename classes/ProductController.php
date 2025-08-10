<?php 
require_once "GenerateID.php";
class ProductController{
    private $productId;
    public function __construct(){
        $db = new DatabaseConnection;
        $this->conn = $db ->conn;
        $this->generateId = new GenerateID;
    }

    /*product color*/
    public function addNewColor($colorData){
        $idType = "color";
        $colorId = $this->generateId->getNewID($idType);
        $colorName = $colorData['name'];
        $colorValue = $colorData['value'];
        $sql_add_color = "INSERT INTO color VALUES('$colorId','$colorName','$colorValue');";
        if($this->conn->query($sql_add_color)){
            $this->generateId->updatetID($idType);
            return true;
        }else{
            return false;
        }
    }
    public function getColorData(){
        $sql_get_color_data = "SELECT * FROM color;";
        $results = $this->conn->query($sql_get_color_data);
        if($results->num_rows > 0){
            return $results;
        }else{
            return false;
        }
    }
    /*product size*/
    public function addNewSize($sizeData){
        $generateId = new GenerateID;
        $idType = "size";
        $sizeId = $this->generateId->getNewID($idType);
        $sizeType = $sizeData['type'];
        $sizeValue = $sizeData['value'];
        $sql_add_size = "INSERT INTO size VALUES('$sizeId','$sizeType','$sizeValue');";
        if($this->conn->query($sql_add_size)){
            $this->generateId->updatetID($idType);
            return true;
        }else{
            return false;
        }
    }
    public function getType(){
        $sql_get_Type = "SELECT Size_Type FROM size GROUP BY Size_Type;";
        $results = $this->conn->query($sql_get_Type);
        if($results->num_rows > 0){
            return $results;
        }else{
            return false;
        }
    }
    public function getSizeData(){
        $sql_get_size_Data = "SELECT * FROM size;";
        $results = $this->conn->query($sql_get_size_Data);
        if($results->num_rows > 0){
            return $results;
        }else{
            return false;
        }
    }
    public function getSizeValue($sizeType){
        $sql_get_Type = "SELECT Size_ID,Size_Value FROM size WHERE Size_Type = '$sizeType';";
        $results = $this->conn->query($sql_get_Type);
        if($results->num_rows > 0){
            return $results;
        }else{
            return false;
        }
    }
    
    /*product Data*/
    private function imgDbFormat($file){
        /*$imageTempName = $file['tmp_Name'];*/
        $imageContent = addslashes(file_get_contents($file));
        return $imageContent;
    }
    public function addNewProduct($productData,$image1,$image2,$image3){
       $idType = "product";
       $this->productId = $this->generateId->getNewID($idType);
       $productName = $productData['pName'];
       $productDesc = $productData['pDesc'];
       $productUnitPrice = $productData['pUnitPrice'];
       $productSalePrice = $productData['pSalePrice'];
       $productImg1 = $this->imgDbFormat($image1);
       $productImg2 = $this->imgDbFormat($image2);
       $productImg3 = $this->imgDbFormat($image3);
       $sql_add_product = "INSERT INTO product VALUES('$this->productId','$productName','$productDesc','$productUnitPrice','$productSalePrice','$productImg1','$productImg2','$productImg3')";
       if($this->conn->query($sql_add_product)){
        $this->generateId->updatetID($idType);
        return true;
       }else{
        return false;
       }
    }
    public function getProductInfomation($productId){
        $sql_get_data = "SELECT * FROM product WHERE Product_ID='$productId'";
        $results = $this->conn->query($sql_get_data);
        if($results->num_rows > 0){
            return $results;
        }else{
            return false;
        }
    }
    public function getallProducts(){
        $sql_get_data = "SELECT p.Product_ID,p.Pro_Name,p.Pro_IMG_1, SUM(s.Stock_Qty) FROM product p, product_variation s WHERE p.Product_ID = s.Product_ID GROUP BY(p.Product_ID)";
        $results = $this->conn->query($sql_get_data);
        if($results->num_rows > 0){
            return $results;
        }else{
            return false;
        }
    }
    public function getallProductsOnMainSub($main,$sub){
        $sql_get_data = "SELECT p.Product_ID,p.Pro_Name,p.Pro_IMG_1, SUM(s.Stock_Qty) FROM product p, product_variation s, categorization c WHERE p.Product_ID = s.Product_ID AND p.Product_ID = c.Product_ID AND c.Main_ID ='$main' AND c.Sub_ID = '$sub' GROUP BY(p.Product_ID)";
        $results = $this->conn->query($sql_get_data);
        if($results->num_rows > 0){
            return $results;
        }else{
            return false;
        }
    }
    public function getallProductsOnMain($main){
        $sql_get_data = "SELECT p.Product_ID,p.Pro_Name,p.Pro_IMG_1, SUM(s.Stock_Qty) FROM product p, product_variation s, categorization c WHERE p.Product_ID = s.Product_ID AND p.Product_ID = c.Product_ID AND c.Main_ID ='$main' GROUP BY(p.Product_ID)";
        $results = $this->conn->query($sql_get_data);
        if($results->num_rows > 0){
            return $results;
        }else{
            return false;
        }
    }
    /* product categoriation*/
    public function categorizeProduct($CategoryData){
        $productId = $this->productId;
        $mainId = $CategoryData['MId'];
        $subId = $CategoryData['SId'];
        $brandId = $CategoryData['BId'];
        $supplyID = $CategoryData['Sup_ID'];
        $sql_categorizeProduct = "INSERT INTO categorization VALUES('$productId','$mainId','$subId','$brandId','$supplyID')";
        if($this->conn->query($sql_categorizeProduct)){
            return true;
        }else{
            return false;
        }
    }
    /* Product stock based on color and size */

    public function addStockInfo($stockData){
        $productId = $stockData['pID'];
        $sizeId = $stockData['sID'];
        $colorId = $stockData['cID'];
        $stockQty = $stockData['qty'];
        $sql_add_stock_info = "INSERT INTO product_variation VALUES('$productId','$sizeId','$colorId','$stockQty')";
        if($this->conn->query($sql_add_stock_info)){
            return true;
        }else{
            return false;
        }
    }
    public function getProductColors($productId){
        $sql_get_data = "SELECT c.Color_ID,c.Color_Value FROM color c,product_variation pv WHERE c.Color_ID = pv.Color_ID AND pv.Product_ID = '$productId' GROUP BY(c.Color_ID)";
        $results = $this->conn->query($sql_get_data);
        if($results->num_rows > 0){
            return $results;
        }else{
            return false;
        }
    }
    public function getProductSize($productId){
        $sql_get_data = "SELECT s.Size_ID,s.Size_Value FROM size s,product_variation pv WHERE s.Size_ID = pv.Size_ID AND pv.Product_ID = '$productId' GROUP BY(s.Size_ID)";
        $results = $this->conn->query($sql_get_data);
        if($results->num_rows > 0){
            return $results;
        }else{
            return false;
        }
    }
    public function getProductDataForStock(){
        $sql_get_data = "SELECT p.Product_ID,p.Pro_Name,p.Pro_Desc FROM product p";
        $results = $this->conn->query($sql_get_data);
        if($results->num_rows > 0){
            return $results;
        }else{
            return false;
        }
    }
    public function getStockQuantity($pid,$sid,$cid){
        $sql_get_data = "SELECT Stock_Qty  FROM product_variation WHERE Product_ID='$pid' AND Size_ID='$sid' AND Color_ID='$cid'";
        $results = $this->conn->query($sql_get_data);
        if($results->num_rows > 0){
            return $results;
        }else{
            return false;
        }
    }

    public function getProductName($productId){
        $sql_get_data = "SELECT Pro_Name FROM product WHERE Product_ID = '$productId';";
        $results = $this->conn->query($sql_get_data);
        if($results->num_rows > 0){
            $row = $results -> fetch_assoc();
            return $row['Pro_Name'];
        }else{
            return false;
        }
    }
    private function getcurrentStockQty($productID,$colorID,$sizeID){
        $sql_get_data = "SELECT Stock_Qty FROM product_variation WHERE Product_ID = '$productID' AND Size_ID = '$sizeID' AND Color_ID = '$colorID' ;";
        $results = $this->conn->query($sql_get_data);
        if($results->num_rows > 0){
            $row = $results -> fetch_assoc();
            return $row['Stock_Qty'];
        }else{
            return false;
        }
    }

    public function reduceFromStock($productID,$colorID,$sizeID,$orderQty){
        $currentStock = $this->getcurrentStockQty($productID,$colorID,$sizeID);
        $updatedStock = 0;
        if($currentStock > 0){
            $updatedStock = $currentStock - $orderQty;
        }else{
            $updatedStock = 0;
        }
        $sql_update = "UPDATE product_variation SET Stock_Qty = '$updatedStock' WHERE Product_ID = '$productID' AND Size_ID = '$sizeID' AND Color_ID = '$colorID'";
        $result = $this->conn->query($sql_update);

    }

    public function getProductDetails(){
        $sql = "SELECT * FROM product";
        $results = $this->conn->query($sql);
        if($results->num_rows > 0){
            return $results;
        }else{
            return false;
        }
    }

    public function getViewProductDetails($id){
        $sql = "SELECT p.*,m.Main_ID,m.name as mName,s.Sub_ID,s.name as sName,b.Brand_ID,b.Name as bName,su.Sup_ID,su.Sup_Name FROM product p, main_category m, sub_category s, brand b, supplier su, categorization c WHERE p.Product_ID = c.Product_ID AND m.Main_ID = c.Main_ID AND s.Sub_ID = c.Sub_ID AND b.Brand_ID = C.Brand_ID AND su.Sup_ID = c.Sup_ID AND p.Product_ID = '$id'";
        $results = $this->conn->query($sql);
        if($results->num_rows > 0){
            return $results;
        }else{
            return false;
        }
    }

    public function getProductVariationOnID($id){
        $sql = "SELECT s.Size_ID,s.Size_Value,c.Color_ID,c.Color_Name,c.Color_Value,pv.Stock_Qty FROM size s, color c, product_variation pv WHERE s.Size_ID = pv.Size_ID AND c.Color_ID = pv.Color_ID AND Product_ID = '$id'";
        $results = $this->conn->query($sql);
        if($results->num_rows > 0){
            return $results;
        }else{
            return false;
        }
    }

    public function updateProductImage($image1,$image2,$image3,$pid){
        $productImg1 = $this->imgDbFormat($image1);
        $productImg2 = $this->imgDbFormat($image2);
        $productImg3 = $this->imgDbFormat($image3);

        $sql = "UPDATE product SET Pro_IMG_1 = '$productImg1',Pro_IMG_2 = '$productImg2',Pro_IMG_3 = '$productImg3' WHERE Product_ID = '$pid'";
        if($this->conn->query($sql)){
            return true;
        }else{
            return $this->conn -> error;
        }
    }

    public function updateProductInfo($NewData){
        $productId = $NewData['pId'];
        $productName = $NewData['pName'];
        $productDesc = $NewData['pDesc'];
        $productUnitPrice = $NewData['pUnitPrice'];
        $productSalePrice = $NewData['pSalePrice'];
        $sql = "UPDATE product SET Pro_Name = '$productName',Pro_Desc = '$productDesc',Pro_UnitPrice = '$productUnitPrice',Pro_SalePrice = '$productSalePrice' WHERE Product_ID = '$productId';";
        if($this->conn->query($sql)){
            return true;
        }else{
            return $this->conn -> error;
        }
    }
    public function updateCategorization($NewData){
        $mainId = $NewData['mainId'];
        $subId = $NewData['subId'];
        $brandId = $NewData['brandId'];
        $supId = $NewData['supId'];
        $productId = $NewData['pId'];
        $sql = "UPDATE categorization SET Main_ID = '$mainId', Sub_ID = '$subId', Brand_ID = '$brandId', Sup_ID = '$supId' WHERE Product_ID = '$productId'";
        if($this->conn->query($sql)){
            return true;
        }else{
            return $this->conn -> error;
        }
    }

    public function getProductVariationData($pid){
        $sql = "SELECT pv.*, s.Size_Type FROM product_variation pv, size s WHERE Product_ID = '$pid' AND pv.`Size_ID` = s.Size_ID";
        $results = $this->conn->query($sql);
        if($results->num_rows > 0){
            return $results;
        }else{
            return false;
        }
    }
    public function updateProductVariation($pid,$sizeID,$colorID,$qty){
        $sql = "UPDATE product_variation SET Size_ID='$sizeID',Color_ID='$colorID',Stock_Qty='$qty' WHERE Product_ID = '$pid'";
        if($this->conn->query($sql)){
            return true;
        }else{
            return $this->conn -> error;
        }
    }

    public function addBackToStock($productID,$colorID,$sizeID,$orderQty){
        $currentStock = $this->getcurrentStockQty($productID,$colorID,$sizeID);
        $updatedStock = $currentStock+$orderQty;
        $sql_update = "UPDATE product_variation SET Stock_Qty = '$updatedStock' WHERE Product_ID = '$productID' AND Size_ID = '$sizeID' AND Color_ID = '$colorID'";
        $result = $this->conn->query($sql_update);
    }

}

?>