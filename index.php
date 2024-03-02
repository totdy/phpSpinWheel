<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />        
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Roll</title>        
        <style>
            html, body{
                width: 100%;
                margin: 0;
            }
            .mainContent{
                display: flex;
                flex-direction: column;
                min-height: 100vh;
                justify-content: space-evenly;
            }
            .spinWrapper{
                overflow:hidden;
                position: relative;
            }
            .spinWrapper:after{
                display: block;
                content: " ";
                position: absolute;
                top: 0;
                left: 50%;
                border-left: 2px solid black;
                height: 100px;
            }
            .spinWrapper:before{
                display: block;
                content: " ";
                position: absolute;
                top: 0;
                left: 0;
                height: 100px;
                width: 100%;
                background: linear-gradient(90deg, white 15%, transparent 20%, transparent 80%, white 85%);
                z-index: 1;
            }
            .spinStripe{
                display: flex; 
                flex-wrap: nowrap; 
                width: fit-content;
                transition-timing-function: cubic-bezier(0, 0.5, 0.2, 1);
                transition-duration: 6s;
            }    
            .prize{
                width: 100px; 
                height: 100px; 
                background: #ffffff; 
                display: block;
            }
            .prize:nth-child(even){
                background: #eeeeee;
            }
            .prizeTitle{
                text-align: center;
                font-size: 3rem;
            }
            .prizeDescription{
                text-align: center;
                font-size: 1rem;
            }
            .rollBtn{
                width: 100px;
                align-self: center;
                padding: 10px;
                border: none;
                background: #000000;
                color: white;
                cursor: pointer;
                font-size: x-large;
                letter-spacing: 2px;
            }
            .rollBtn:hover {
                transform: scale(1.1);
            }
        </style>
    </head>
    <body>
        <div class="mainContent">
        <?php

        require_once 'airtable.class.php';
        
        $prizes = new airtable("app7ygGlUhQfEIiWP","tblSaA8PdZtjuFv4z","patz43r5amyBkWyMC.6dee36125e16808baac79bdf13a0e69bdc5e57ddedd990929e3769bed7d5c53c");

        $tmpPrizes = $prizes->getRecords();
            
        $tmpPrizes = $tmpPrizes != false ? json_decode($tmpPrizes) : null ;

        $aPrizes = [0];    

        foreach($tmpPrizes->records as $row ){
            $aPrizes[] = array($row->fields->prizeId, $row->fields->name, $row->fields->description);    
        }
        
        /*
        $aPrizes = [0];

        for ($i = ord('A'); $i <= ord('Z'); $i++) {
            $aPrizes[] = array(0, chr($i), "Prize " . chr($i));
        }
        */

        unset($aPrizes[0]);
        
        $totalPrizes = count($aPrizes);
        $prizeWon = rand(0, $totalPrizes - 1);        
        
        $email = isset($_GET["email"]) ? htmlspecialchars($_GET["email"], ENT_QUOTES, 'UTF-8') : '';

        if (!empty($email) && preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,})+$/", $email)) {
            
            $winer = new airtable("app7ygGlUhQfEIiWP","tbldbMtm8IieZqJkV","patz43r5amyBkWyMC.6dee36125e16808baac79bdf13a0e69bdc5e57ddedd990929e3769bed7d5c53c");
            
            $tmp = $winer->createRecord([
                "email" => $email,
                "prizeId" => $aPrizes[$prizeWon][0]
            ]);

        }        
        
        echo "<div class='spinWrapper'><div class='spinStripe'>";
        
        $count=0;
        for($i=0;$i<5;$i++){
            foreach($aPrizes as $key=>$item){
                $count++;
                echo "<div class='prize' id='".$count."'><div class='prizeTitle'>".$item[1]."</div><div class='prizeDescription'>".$item[2]."</div></div>";
            }
        }

        echo "</div></div><button class='rollBtn' onclick='roll(".(($totalPrizes*3+$prizeWon)*100).");'>click</button>";
        ?>
        </div>
    </body>
    <script>
        function roll(pos){
            let spinStripe = document.getElementsByClassName("spinStripe")[0];
            spinStripe.style.transform = "translate3d(calc(0px - "+pos+"px + 50px + 50vw), 0px, 0px)";                       
        }
    </script>
</html>