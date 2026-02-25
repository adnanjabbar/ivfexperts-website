#!/bin/bash

mkdir -p male-infertility female-infertility art-procedures contact about
mkdir -p assets/css assets/js includes

touch assets/css/style.css
touch assets/js/app.js
touch sitemap.php

# Male pages
touch male-infertility/{index.php,low-sperm-count.php,azoospermia.php,dna-fragmentation.php,varicocele.php}

# Female pages
touch female-infertility/{index.php,pcos.php,endometriosis.php,blocked-tubes.php,diminished-ovarian-reserve.php}

# ART pages
touch art-procedures/{index.php,ivf.php,icsi.php,iui.php,pgt.php}

echo "Structure generated successfully."