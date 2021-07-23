<?php

class main{

  function __construct(){
    $this->alliance=array("01ALLIANCE_COCC"=>"Central Oregon Community College",
    "01ALLIANCE_CWU"=>"Central Washington University",
    "01ALLIANCE_CHEMEK"=>"Chemeketa Community College",
    "01ALLIANCE_CCC"=>"Clackamas Community College",
    "01ALLIANCE_CC"=>"Clark College",
    "01ALLIANCE_CONC"=>"Concordia University",
    "01ALLIANCE_EOU"=>"Eastern Oregon University",
    "01ALLIANCE_EWU"=>"Eastern Washington University",
    "01ALLIANCE_GFOX"=>"George Fox University",
    "01ALLIANCE_LANECC"=>"Lane Community College",
    "01ALLIANCE_LCC"=>"Lewis & Clark",
    "01ALLIANCE_LINF"=>"Linfield College",
    "01ALLIANCE_MHCC"=>"Mt Hood Community College",
    "01ALLIANCE_OHSU"=>"Oregon Health & Science University",
    "01ALLIANCE_OIT"=>"Oregon Institute of Technology",
    "01ALLIANCE_OSU"=>"Oregon State University",
    "01ALLIANCE_PU"=>"Pacific University",
    "01ALLIANCE_PCC"=>"Portland Community College",
    "01ALLIANCE_PSU"=>"Portland State University",
    "01ALLIANCE_REED"=>"Reed College",
    "01ALLIANCE_STMU"=>"Saint Martin's University",
    "01ALLIANCE_SPU"=>"Seattle Pacific University",
    "01ALLIANCE_SEAU"=>"Seattle University",
    "01ALLIANCE_SOU"=>"Southern Oregon University",
    "01ALLIANCE_EVSC"=>"The Evergreen State College",
    "01ALLIANCE_UID"=>"University of Idaho",
    "01ALLIANCE_UO"=>"University of Oregon",
    "01ALLIANCE_UPORT"=>"University of Portland",
    "01ALLIANCE_UPUGS"=>"University of Puget Sound",
    "01ALLIANCE_UW"=>"University of Washington",
    "01ALLIANCE_WALLA"=>"Walla Walla University",
    "01ALLIANCE_WPC"=>"Warner Pacific College",
    "01ALLIANCE_WSU"=>"Washington State University",
    "01ALLIANCE_WOU"=>"Western Oregon University",
    "01ALLIANCE_WWU"=>"Western Washington University",
    "01ALLIANCE_WHITC"=>"Whitman College",
    "01ALLIANCE_WW"=>"Whitworth University",
    "01ALLIANCE_WU"=>"Willamette University");





  }

  function switchboard($state){


    switch($state){
      case "start":

      $this->start();
      break;

      case "scan":
      $this->scan();
      break;

      case "done":
      $this->done();
      break;

    }


  }

  function start(){

    $alliance=$this->alliance;
    ?>
<form action="index.php" method="post">
  <div class="form-group">
    <label for="allianceMember">Select your institution</label>
    <select class="form-control form-control-lg" id="allianceMember" name="memberCode">
    <?php
    foreach($alliance as $code=>$member){
      ?>
      <option value="<?=$code?>"><?=$member?></option>
      <?php

    }
     ?>


    </select>
  </div>


<div class="form-group">
    <button type="submit" class="btn btn-primary" >Begin a new text file of barcodes</button>
<input type="hidden" name="state" value="scan">

</div>
</form>
<hr>





    <?php

  }

  function scan(){
?>
    <div class="container">
      <div class="row">
        <p>Home institution: <?= $_SESSION["memberInst"]?>.</p>

      </div>


  <div class="row">
    <div class="col-4">

      <form action="index.php" method="post">
          <div class="form-group">
            <label for="bc" class="sr-only">Scan Barcode</label>
            <input type="text" name="bc" id="bc" class="form-control" required >
          </div>
          <input name="state" value="readBarcode" type="hidden">

          <input style="background-color:#00FFFF;" name="login" id="login" class="btn btn-block login-btn mb-4" type="submit" value="Scan">
        </form>


        <p><a href="index.php?state=done">I'm done scanning, and ready to download the text file.</a></p>

    </div>
    <div class="col-8">
      <?php
        if(count($_SESSION["barcodes"]>0)){
          foreach($_SESSION["barcodes"] as $bc){
            echo "$bc<br>";
          }
        }
       ?>
    </div>

  </div>
</div>

<?php


 ?>

<script>
  document.getElementById("bc").focus();

</script>


<?php




  }

  function done(){
    /* write file, present link, kill session     */
    if($this->makeFile()){
      $f=$_SESSION["file"];
      $path="files/$f";

      echo "<p><a href='dl.php?file=$f'>Download File</a></p>";

      unset($_SESSION["barcodes"]);
      unset($_SESSION["active"]);
      unset($_SESSION["file"]);
      unset($_SESSION["memberCode"]);
      unset($_SESSION["memberInst"]);

      $this->start();


    }

  }

  function makeFile(){
    $f=$_SESSION["file"];

    $myfile = fopen("files/$f", "w") or die("Unable to open file!");
    foreach($_SESSION["barcodes"] as $barcode){
      $txt = "$barcode\r\n";
      fwrite($myfile, $txt);
    }

    if (fclose($myfile)){
      return true;
    }

  }





  function initiate(){
    $code=$_POST["memberCode"];
    $_SESSION["active"]=true;
    $_SESSION["memberCode"]=$code;
    $_SESSION["memberInst"]=$this->alliance[$code];
    $t=time();

    $myfile = fopen("files/$t.txt", "w");
    $_SESSION["file"]="$code-$t.txt";
    $_SESSION["barcodes"]=array();

  }





    function checkLastCopyNetworkId($networkId){
      $alliance=$this->alliance;
      $url="https://na01.alma.exlibrisgroup.com/view/sru/01ALLIANCE_NETWORK?version=1.2&operation=searchRetrieve&query=alma.mms_id=$networkId";
      /* make SRU call  */
      $xml=simplexml_load_file($url);
      $result="";
      $x=0;
      $r=$xml->records->record->recordData->record;
      /* loop through datafields */
      foreach ($r->datafield as $field){
        /* find datafields with tag 852   */
        if($field->attributes()->tag=="852"){
          /* get and print Alliance Member code*/
          $loc=$field->subfield[0];

          $library=$alliance["$loc"];
          //$result.=$library.", ";
          $x++;


        }

      }
      //echo $x;
      if($x>1){return false;} //not the last copy
      else{return true;}  //the last copy!



    }


    function readBarcodeNetworkId(){
      $barcode=$_POST["bc"];
      if($networkId=$this->getNetworkId($barcode)){
        if($this->checkLastCopyNetworkId($networkId)){
          //it's the last copy!
        //  echo "Last copy!!";
          $_SESSION["flashtype"]="danger";
          $_SESSION["flash"]="$barcode: Last Copy!! DO NOT WEED!!";
        }
        else{
            $_SESSION["flashtype"]="success";
            $_SESSION["flash"]="$barcode: Ok to weed";

            # add barcode to text file
            if(!in_array($barcode, $_SESSION["barcodes"])){
              array_push($_SESSION["barcodes"], $barcode);
            }
        }

      }
      else{
          $_SESSION["flashtype"]="secondary";
        $_SESSION["flash"]="Barcode $barcode not found.";
      }
      header('Location: http://watzek.lclark.edu/weeding/index.php?state=scan');
      exit;

    }

    function getNetworkId($barcode){
      $url="https://na01.alma.exlibrisgroup.com/view/sru/".$_SESSION["memberCode"]."?version=1.2&operation=searchRetrieve&query=alma.barcode=$barcode";

      $xml=simplexml_load_file($url);
      $record=$xml->records->record->recordData->record;
      //var_dump($record);
      foreach ($record->datafield as $df){
        if($df->attributes()->tag=="035"){
          $o=$df->subfield;
          #echo $o;

          if(strpos($o, "NETWORK)")){
            $a=explode("NETWORK)", $o);
            #echo $a[1];

            //exit();
            return $a[1];
            break;
          }
          else{

            #echo "didn't";
          }
        }

      }
    }



}

?>
