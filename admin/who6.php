<?php
function interpret($count,$motility,$morphology){
  if($count==0){ return "Azoospermia"; }
  if($count<15){ return "Oligospermia"; }
  if($motility<40){ return "Asthenozoospermia"; }
  if($morphology<4){ return "Teratozoospermia"; }
  return "Normozoospermia";
}
?>
