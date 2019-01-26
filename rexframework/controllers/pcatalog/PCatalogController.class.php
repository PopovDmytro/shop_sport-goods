<?php

class PCatalogController extends \RexShop\PCatalogController
{
    public static $assemble = 'volley.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexShop\PCatalogController:shop.standart:1.0',
        'RexShop\PCatalogEntity:shop.standart:1.0',
        'RexShop\PCatalogManager:shop.standart:1.0',
        'RexShop\ProductManager:shop.standart:1.0',
        'RexShop\Brand2CatManager:shop.standart:1.0',
        'RexShop\BrandManager:shop.standart:1.0',
        'RexShop\CartManager:shop.standart:1.0',
        'RexShop\SkuManager:shop.standart:1.0',
        'PagerObj:standart:1.0'
    );

    function getAutocomplete()
    {
        $query = Request::get('q', false);
        $category = Request::get('category', false);
        if (!$query or strlen($query) < 2) {
            exit;
        }
        $query = strtolower($query);
        $query = preg_replace('#[^a-zа-я0-9\s-\/\'\"\(\)\[\]\`\,]#isu', '', $query);
        //die(var_dump($query));
        $pattern = '/^([0-9ry)]+)-([0-9]+)$/';
        preg_match($pattern, $query, $matches);
        $sku_id = false;
        $clear_domain = RexConfig::get('Project', 'cookie_domain');
        if ($matches) {
            $query = $matches[0];
            $sku_id = $matches[2];
            setcookie('sku_id', $sku_id, time() + 18000, '/', $clear_domain);
        } else {
            $pattern = '/^([0-9]+)$/';
            preg_match($pattern, $query, $matches);
            if ($matches) {
                $sku_id = true;
            }
        }  
        $productManager = RexFactory::manager('product');
        $productManager->getByCategorySearch(0, 20, $query, $sku_id, $category);
        $res = $productManager->getCollection();
        $size = sizeof($res);
        if ($res and $size > 0) {
            foreach ($res as $key=>$value) {
                if($value['sale']>0){
                    $full_price = $value['price'];  
                } else {
                     $full_price = '';
                }
                echo XImage::getImg(array('type' => 'list', 'name' => 'pImage', 'id' => $value['image_id'], 'ext' => $value['image_ext'])).'='.
                    $value['category_alias'].'/'.$value['product_id'].'.html='.$value['product_name'].'='.floor($value['price'] - $value['price']*$value['sale']/100).'='.$full_price."\n";
            }
            
             echo 'lastitems='.'/search/'.$query.'/=все результаты поиска';
        }
        exit;
    }

    function getDefault()
    {
        $rexRequest = false;

        if (RexResponse::isRequest()) {
            $rexRequest = true;
            RexResponse::init();
        }

        $rexRequestModal = Request::get('skinDefault', false);

        $valuta = RexSettings::get('filter_kurs');
        RexDisplay::assign('valuta', $valuta);
        RexDisplay::assign('dolar_rate', RexSettings::get('dolar_rate', 1));

        $parseUrl = parse_url($_SERVER['REQUEST_URI']);
        RexDisplay::assign('parseUrl', $parseUrl);

        $this->entity = RexFactory::entity('pCatalog');
        if (!$this->entity->getByFields(array('alias' => $this->task))) {
            header('HTTP/1.0 404 Not Found');
            header('location: /404');
            exit;
        }

        RexDisplay::assign('pcatalog', $this->entity);

        $clearfilter = Request::get('clearfilter', false);

        if ($clearfilter) {
            $url = RexRoute::getUrl('pCatalog', 'default', $this->entity->alias);
            //
            header('location: '.$url);
            exit;
        }

        $filter = Request::get('filter', false);

        $page = Request::get('page', 1);

        $uri= Request::get('uri', false);
        RexDisplay::assign('uri', $uri);
        //var_dump($uri);
        if ($uri && $uri != '?') {
            $brandEntity = RexFactory::entity('brand');
            $brand_id = preg_replace("([^0-9])", "", $uri);
            $brandEntity->getByWhere('id = '.$brand_id);
            RexDisplay::assign('brand_content', $brandEntity->content);
        }

        $brand_alias = Request::get('brand_alias', false);
        if($_SERVER['REMOTE_ADDR'] = '77.120.163.26'){
                    //\sys::dump($brand_alias);exit;
        }

        if ($brand_alias) {
            $brandEntity = RexFactory::entity('brand');
            $brandEntity->getByFields(array('alias' => $brand_alias));
            if ($brandEntity->id) {
                $filter['brand'][] = $brandEntity->id;
                RexDisplay::assign('brandName', $brandEntity->name);

                //\sys::dump($this->entity->name);exit;
                RexDisplay::assign('brand', $brandEntity);
            }
            RexDisplay::assign('brand_alias', $brand_alias);
            if ($this->task == 'nakolenniki' and $brand_alias == 'asics') {
                RexPage::setTitle('Волейбольные наколенники ASICS. Наколенники Асикс купить в магазине Волеймаг');
                RexPage::setDescription('Профессиональные и любительские волейбольные наколенники ASICS - большой выбор размеров и цветов. Купить волейбольные наколенники Асикс по лучшей цене в Харькове с доставкой');
                RexPage::setKeywords('Волейбольные наколенники ASICS, волейбольные наколенники Асикс купить');
            } else if ($this->task == 'nakolenniki' and $brand_alias == 'mizuno') {
                RexPage::setTitle('Профессиональные и любительские волейбольные наколенники Mizuno в Харькове');
                RexPage::setDescription('Лучшие цены на наколенники для волейбола Mizuno в магазине Волеймаг. Заказать наколенники Мизуно разных цветов и размеров, с боковой защитой и без нее. Волейбольные наколенники Mizuno с доставкой по Украине');
                RexPage::setKeywords('Волейбольные наколенники Mizuno, наколенники для волейбола Mizuno, наколенники Мизуно');
            } else if ($this->task == 'mjachi' and $brand_alias == 'mikasa') {
                RexPage::setTitle('Волейбольные мячи Mikasa в магазине Волеймаг. Купить мяч Mikasa в Харькове с доставкой');
                RexPage::setDescription('Волейбольные мячи Mikasa в магазине Волеймаг. Купить мяч Mikasa в Харькове с доставкой');
                RexPage::setKeywords('Волейбольные мячи Mikasa, купить мяч Mikasa, купить мяч Mikasa, Mikasa MVA200');
            } else if ($this->task == 'mjachi' and $brand_alias == 'select') {
                RexPage::setTitle('Футзальные и футбольные мячи Select. Купить мяч Select из натуральной кожи');
                RexPage::setDescription('Мячи Селект для обычного и мини-футбола в магазине Волеймаг. Лучшие цены на профессиональные и любительские мячи Select. Купить футзальный мяч Select по выгодной цене в Харькове с доставкой на дом');
            } else if ($this->task == 'obyv-dlja-bega' and $brand_alias == 'asics') {
                RexPage::setTitle('Профессиональные кроссовки Asics для бега. Купить кроссовки Асикс в Харькове');
                RexPage::setDescription('Мужские и женские кроссовки Asics на сайте магазина Волеймаг по лучшим ценам. Выбрать и заказать Asics - лучшую обувь для бега. Купить Асикс в Украине с доставкой из Харькова');
                RexPage::setKeywords('Кроссовки Asics, купить кроссовки Асикс, женские кроссовки Asics, купить Асикс в Украине');
            } else if ($this->task == 'obyv-dlja-bega' and $brand_alias == 'mizuno') {
                RexPage::setTitle('Женские и мужские кроссовки Mizuno для бега. Купить кроссовки Мизуно в Харькове');
                RexPage::setDescription('Лучшие цены на профессиональные беговые кроссовки Mizuno. Кроссовки для бега в зале и на природе. Купить Mizuno для бега в интернет-магазине спортивных товаров Волеймаг');
                RexPage::setKeywords('Кроссовки Mizuno для бега, купить кроссовки Мизуно, купить кроссовки Мизуно');
            } else {
                RexPage::setTitle($this->entity->name.' '.$brandEntity->name.' в интернет-магазине Волеймаг');
                RexPage::setDescription('Лучшие цены на спортивные товары '.$brandEntity->name.' в Харькове. '.$this->entity->name.' '.$brandEntity->name.' купить в магазине Волеймаг');
                RexPage::setKeywords($this->entity->name.' '.$brandEntity->name);
                //\sys::dump();exit;
            }
            
            if ($this->task == 'krossovki-dlja-volejbola-i-gandbola' and $brand_alias == 'mizuno') {
                RexDisplay::assign('brand_content2', "<p>Экипировка – важнейший элемент в жизни спортсмена в Украине. Особую роль в спорте играет обувь, поэтому купить стоит только качественные изделия от проверенных производителей. К таким как раз относится популярная марка Mizuno. Многие любители гандбола и волейбола уже успели оценить все преимущества обуви от этого бренда.</p> <p>Волейбольные кроссовки Mizuno отличаются высоким качеством и повышенным уровнем удобства. Производство при помощи современных технологий позволяет этой обуви значительно отличаться в лучшую сторону от конкурирующих торговых марок. Если вы выберете Mizuno волейбольные, то вы сможете в полной мере насладиться всеми эмоциями, которые дарит волейбольный матч. У нас вы можете заказать Mizuno кроссовки Wave!</p>");
            }
            if ($this->task == 'krossovki-dlja-volejbola-i-gandbola' and $brand_alias == 'asics') {
                RexDisplay::assign('brand_content2', "<p>Выбор обуви для волейбола и гандбола – ответственный процесс. Среди разнообразия брендов сложно сделать правильный выбор. Многие спортсмены Украины уже остановились на такой спортивной обуви как кроссовки волейбольные Asics. Репутация этой торговой марки безупречна.</p><p>Купить изделия этого бренда торопятся спортсмены, знающие цену настоящего олимпийского качества. Волейбольные товары Asics позволяют спортсмену превосходно чувствовать себя во время игры. Вместе с этой обувью можно сосредоточиться исключительно на игре и получить множество эмоций, которые дарит матч. Предлагаем вам купить Asics gel и насладиться идеальной обувью!</p>");
            }
            if ($this->task == 'nakolenniki' and $brand_alias == 'asics') {
                RexDisplay::assign('brand_content2', "<p>Многие люди, так или иначе связанные со спортом, к сожалению, рано или поздно сталкиваются с травмами колена и их профилактикой. Поэтому наколенники Асикс стали неотъемлемой частью экипировочного арсенала спортсменов Украины. Эта торговая марка заслужила репутацию верного друга и помощника множества спортсменов.</p><p>Волейбольные наколенники Asics - гордость многих волейбольных команд. Этот вид профессиональной экипировки эффективно используется не только волейболистами, поэтому купить её стоит каждому человеку, связанному с командными играми или другим спортом. Наколенники Asics – лучший выбор украинских спортсменов!</p>");
            }
            if ($this->task == 'nakolenniki' and $brand_alias == 'mizuno') {
                RexDisplay::assign('brand_content2', "<p>Боли в связках и суставах часто становятся верными спутниками спортсменов в Украине и не только. Однако, к счастью, решение проблемы всё-таки существует. Это наколенники Mizuno. Они фиксируют и стабилизируют коленный сустав, поддерживают мениск и боковые связки.</p><p>Волейбольные наколенники Mizuno - не просто «лекарство» от болей. Это вложение денег в свое здоровье. Купить такую спортивную экипировку решает всё большее количество спортсменов. Если вы занимаетесь спортом и у вас есть определённые проблемы с коленями, то рекомендуем вам выбрать наколенники Мизуно.</p>");
            }
            if ($this->task == 'mjachi' and $brand_alias == 'mikasa') {
                RexDisplay::assign('brand_content2', "<p>Мяч – то, без чего невозможно проведение большинства спортивных игр. Поэтому требования к нему достаточно высоки. Долговечность считается одной из важнейших характеристик этого вида экипировки. Именно таков мяч Mikasa. Он изготовлен из качественных материалов на ультрасовременном оборудовании.</p><p>Волейбольные мячи Mikasa по праву стали любимчиками многих команд Украины. Игра с ними – процесс, приносящий настоящее удовольствие. Многие решают купить их и не разочаровываются в своём выборе. Мячи микаса – важная деталь большого спорта. Предлагаем вам приобрести волейбольные мячи Микаса по выгодным ценам!</p>");
            }
            if ($this->task == 'mjachi' and $brand_alias == 'select') {
                RexDisplay::assign('brand_content2', "<p>Мячи Select давно стали выбором многих спортсменов в Украине – как профессионалов, так и любителей. Купить их стоит каждому человеку, для кого слово «спорт» вовсе не пустой звук. Украинские футболисты давно оценили преимущества этого вида экипировки.</p><p>Футбольный мяч Select считается одним из лучших по сравнению с другими торговыми марками. Они обладают массой преимуществ. Почему же именно мячи Селект? Главная их отличительная черта – высочайшее качество. Достичь таких высоких показателей качества производители смогли благодаря отличным материалам и современным технологиям, используемым при изготовлении своей продукции. Предлагаем вам купить мяч Select в нашем интернет-магазине спортивной экипировки!</p>");
            }
            if ($this->task == 'obyv-dlja-bega' and $brand_alias == 'asics') {
                RexDisplay::assign('brand_content2', "<p>Бег – один из самых популярных видов спорта в Украине. К своей экипировке бегуны подходят очень внимательно и стараются выбрать только максимально надёжные элементы. Большим доверием бегунов пользуются кроссовки Асикс. Репутация этого бренда безукоризненна. Купить Asics стоит каждому спортсмену, который ценит своё здоровье и удобство во время тренировок.</p><p>Обувь этого бренда выбирают те люди, которые предъявляет высокие требования к своей экипировке. Asics Украина – лучшее решение для бега. Тем более в нашем магазине предлагается только обувь от производителя – среди нашего ассортимента исключительно оригинальные кроссовки Asics.</p>");
            }
            if ($this->task == 'obyv-dlja-bega' and $brand_alias == 'mizuno') {
                RexDisplay::assign('brand_content2', "<p>Специальная обувь для бега – не роскошь, а необходимость для профессиональных спортсменов и любителей. От неё зависит удобство и здоровье бегуна. Кроссовки Mizuno позволят вам полностью сосредоточиться на процессе бега и насладиться своими ощущениями от физических нагрузок.</p><p>Обувь разработана в соответствии со всеми современными требованиями Украины и мировыми стандартами. Высокое качество, созданное благодаря современным технологиям – главная причина купить кроссовки Мизуно. Они полностью соответствуют всем целям и задачам, которые ставятся перед профессиональной обувью для бега.</p><p>Купить Мизуно в Украине можно по самым выгодным ценам, если обратиться в наш интернет магазин спортивной экипировки!</p>");
            }

        }

        RexDisplay::assign('filterNow', $filter);

        // generate list of filters
        //\sys::dump($filter); exit;
        if ($filter && count($filter) > 0) {

            foreach ($filter as $filterKey => $filterValue) {

                if ($filterKey == 'rangefrom' && $filter['rangefrom'] > 0 && $filter['rangeto'] > 0) {
                    $filterSelected['price']['name'] = 'Цена';
                    $filterSelected['price']['value'] = 'От '.$filter['rangefrom'].' '.$valuta.' до '.$filter['rangeto'].' '.$valuta;
                } elseif ($filterKey == 'attribute' && is_array($filter['attribute']) && count($filter['attribute']) > 0) {

                    foreach ($filterValue as $attrKey => $arrayValues) {
                        $attributeEntity = RexFactory::entity('attribute');
                        $attributeEntity->get($attrKey);
                        $filterSelected['attr_'.$attrKey]['name'] = $attributeEntity->name;

                        if (9 == $attributeEntity->type_id) {
                            $attributeEntityNew = RexFactory::entity('attribute');
                            $attributeEntityNew->getByWhere('pid = '.$attributeEntity->id.' ORDER BY `name` DESC LIMIT 1');
                            $filterSelected['attr_'.$attrKey]['val_name'] = $attributeEntityNew->name;
                        }

                        foreach ($arrayValues as $attrValue) {

                            switch ($attributeEntity->type_id) {
                                case 9:

                                    if (isset($filterSelected['attr_'.$attrKey]['val_min'])) {

                                        if ($filterSelected['attr_'.$attrKey]['val_min'] > $attrValue) {
                                            $filterSelected['attr_'.$attrKey]['val_max'] = $filterSelected['attr_'.$attrKey]['val_min'];
                                            $filterSelected['attr_'.$attrKey]['val_min'] = $attrValue;
                                        } else {
                                            $filterSelected['attr_'.$attrKey]['val_max'] = $attrValue;
                                        }

                                    } else {
                                        $filterSelected['attr_'.$attrKey]['val_min'] = $attrValue;
                                    }

                                    break;
                                case 7:
                                case 6:
                                    $attributeEntityNew = RexFactory::entity('attribute');
                                    $attributeEntityNew->get($attrValue);
                                    $filterSelected['attr_'.$attrKey]['val_'.$attrValue] = $attributeEntityNew->name;
                                    break;

                                case 4:
                                case 3:
                                case 2:
                                    $filterSelected['attr_'.$attrKey]['val_'.$attrValue] = $attrValue;
                                    break;

                                case 5:
                                    if (1 === intval($attrValue)) {
                                        $filterSelected['attr_'.$attrKey]['val_'.$attrValue] = 'Да';
                                    } elseif (0 === intval($attrValue)) {
                                        $filterSelected['attr_'.$attrKey]['val_'.$attrValue] = 'Нет';
                                    } else {
                                        unset($filterSelected['attr_'.$attrKey]);
                                    }
                                    break;

                            }

                        }

                    }
                } elseif ($filterKey == 'brand') {
                    $filterSelected[$filterKey]['name'] = 'Производитель';

                    //\sys::dump($filterValue); exit;
                    foreach ($filterValue as $brandID) {
                        $brandEntity = RexFactory::entity('brand');
                        $brandEntity->getByWhere('id = "'.$brandID.'"');
                        $filterSelected[$filterKey]['value'][$brandID] = $brandEntity->name;
                    }

                }
            }
            if (isset($filterSelected)) {
                RexDisplay:assign('filterSelected', $filterSelected);
            }
        }

        // fetch filters templates
        $checked_list = array();

        if (isset($filter['attribute'])) {
            foreach ($filter['attribute'] as $attrID => $values) {
                $attributeEntity = RexFactory::entity('attribute');
                $attributeEntity->get($attrID);

                foreach ($values as $val) {
                    if (intval($val) === 3 && $attributeEntity->type_id == 5) {
                        unset($filter['attribute'][$attrID]);
                        continue;
                    }
                    $checked_list[md5($attrID.'_'.$val)] = 1;
                }
            }
        }

        RexDisplay::assign('checked_list', $checked_list);

        /*$brand = Request::get('brand', false);
        if ($brand) {
            $filter['brand'] = $brand;
        }

        if (isset($filter['brand'])) {
            RexDisplay::assign('brandName', $filter['brand']);
            $brandEntity = RexFactory::entity('brand');
            $brandEntity->getByWhere('alias = "'.$filter['brand'].'"');
            RexDisplay::assign('brand', $brandEntity);
        }*/
        if (!$brand_alias) {
            if ($this->entity->title) {
                if ($page > 1) {
                    RexPage::setTitle($this->entity->title.' - страница '.$page);
                } else {
                    RexPage::setTitle($this->entity->title);
                }
            }elseif ($this->entity->name){
                RexPage::setTitle($this->entity->name);
            } else {
                RexPage::setTitle(RexSettings::get('site_slogan'));
            }
            if ($page > 1) {
                RexPage::setDescription($this->entity->description.' - страница '.$page);
            } else {
                RexPage::setDescription($this->entity->description);
            }
            RexPage::setKeywords($this->entity->keywords);
        }


        //get all subcategories
        $this->manager = RexFactory::manager('pCatalog');
        $this->manager->getSubCategoriesList($this->entity->id, 1);
        $categoryList = $this->manager->struct;
        if ($categoryList and sizeof($categoryList) > 0) {
            $this->manager->getByWhere('id in ('.implode(',', $categoryList).') order by gorder');
            RexDisplay::assign('subcategories', $this->manager->getCollection());
            $fullList = $categoryList;
            $fullList[] = $this->entity->id;
        } else {
            $fullList = array($this->entity->id);
        }

        //brand list
        $brand2Cat = RexFactory::manager('brand2Cat');
        $brand2Cat->getByWhere('category_id in ('.implode(',', $fullList).')');
        if ($brand2Cat->_collection and sizeof($brand2Cat->_collection) > 0) {
            $brand2CatData = '';
            foreach ($brand2Cat->getCollection() as $b2c) {
                $brand2CatData .= $b2c['brand_id'].',';
            }
            $brand2CatData = trim($brand2CatData, ',');
            $brandManager = RexFactory::manager('brand');
            $brandManager->getByWhere('id in ('.$brand2CatData.') order by name');
            if ($brandManager->_collection and sizeof($brandManager->_collection) > 0) {
                $res = $brandManager->getCollection();

                if (isset($filter['brand'])) {

                    foreach ($filter['brand'] as $brandID) {

                        foreach ($res as &$brandArray) {

                            if ($brandArray['id'] == $brandID) {
                                $brandArray['selected'] = 1;
                            }
                        }
                    }
                }

                RexDisplay::assign('categoryBrandList', $res);
            }
        } elseif ($this->entity->pid > 0) {
            $brand2Cat = RexFactory::manager('brand2Cat');
            $brand2Cat->getByWhere('category_id = '.$this->entity->pid);
            if ($brand2Cat->_collection and sizeof($brand2Cat->_collection) > 0) {
                $brand2CatData = '';
                foreach ($brand2Cat->getCollection() as $b2c) {
                    $brand2CatData .= $b2c['brand_id'].',';
                }
                $brand2CatData = trim($brand2CatData, ',');
                $brandManager = RexFactory::manager('brand');
                $brandManager->getByWhere('id in ('.$brand2CatData.') order by name');
                if ($brandManager->_collection and sizeof($brandManager->_collection) > 0) {
                    $res = $brandManager->getCollection();

                    if (isset($filter['brand'])) {

                        foreach ($filter['brand'] as $brandID) {

                            foreach ($res as &$brandArray) {

                                if ($brandArray['id'] == $brandID) {
                                    $brandArray['selected'] = 1;
                                }
                            }
                        }
                    }

                    RexDisplay::assign('categoryBrandList', $res);
                }
            }
        }

        //show product list
        //$page = Request::get('page', 1);
        $perPage = RexSettings::get('per_page');
        $pagerObj = new PagerObj('pager', $perPage, $page);
        $pagelast = Request::get('pagelast');

        $from = $pagerObj->getFrom();
        $count = $pagerObj->getPerPage();
        if ($pagelast && $pagelast != $page) {
            $count = ($pagelast - $page +1) * $perPage;
            //echo  $count; exit;
        }

        $productManager = RexFactory::manager('product');
        //var_dump($filter);exit; 28.07

        $productManager->getByCategoryListFilter($fullList, $from, $count, $filter);
        RexDisplay::assign('price_order', isset($filter['price_order'])?$filter['price_order']:'DESC');
        $productList = $productManager->getCollection();
        $imageList = $productManager->images;
        $categoryList = $productManager->categories;
        $brandList = $productManager->brands;

        if (sizeof($productList) > 0) {
            $color = array();
            $skuManager = RexFactory::manager('sku');
            $catalogGender = RexFactory::manager('product');
            foreach($productList as $kk => $vv)  {
                $productList[$kk]['content'] = strip_tags($vv['content']);
                $skuByColor = $skuManager->getSkusFrontMainPage($vv['id']);
                if ($skuByColor) {
                    $color[$vv['id']] = $skuByColor;
                }
            }

            RexDisplay::assign('prodColor', $color);
            RexDisplay::assign('productList', $productList);

            RexDisplay::assign('imageList', $imageList);
            RexDisplay::assign('categoryList', $categoryList);
            RexDisplay::assign('brandList', $brandList);

            $pagerObj->setCount($productManager->_count);
            $pagerObj->generatePages();
            RexDisplay::assign($pagerObj->name, $pagerObj);
            RexDisplay::assign('currentPage', $pagerObj->currentPage);
            RexDisplay::assign('pager_count', count($pagerObj->pages));

            $count_more = $productManager->_count - ($page * $perPage);
            $count_next = $count_more > $perPage ? $perPage : $count_more;
            //var_dump($productManager->_count);exit;
            if ($rexRequest) {
                $reponse['count_next'] = $count_next;
            }

            RexDisplay::assign('count_next', $count_next);
        }

//        var_dump($productManager->_productListIDs);exit;
        $fetched = $this->manager->getFilters($this->entity->id, $productManager->_productListIDs);
        if ($fetched !== false) {
            RexDisplay::assign('filter_form', $fetched);
        }
        RexDisplay::assign('instant_filter', RexSettings::get('filter_instant') == 'true');
        // calculate pricerange
        $pricerange = $productManager->getByCategoryListFilterPrices($fullList, $filter);

        if ($pricerange) {

            if (isset($filter['rangefrom']) && isset($filter['rangeto']) && $filter['rangefrom'] <= $filter['rangeto']) {
                $pricerange['pricefrom'] = $filter['rangefrom'];
                $pricerange['priceto'] = $filter['rangeto'];
            } else {
                $pricerange['pricefrom'] = $pricerange['rangefrom'];
                $pricerange['priceto'] = $pricerange['rangeto'];
            }
            //\sys::dump($pricerange['rangeto']);exit;
            RexDisplay::assign('pricerange', $pricerange);

        } else if (!$pricerange && isset($filter['rangefrom']) && isset($filter['rangeto'])) {
            $pricetemp['pricefrom'] = $filter['rangefrom'];
            $pricetemp['priceto'] = $filter['rangeto'];
            unset($filter['rangeto']);
            unset($filter['rangefrom']);

            $priceget = $productManager->getByCategoryListFilterPrices($fullList, $filter);

            if ($priceget) {
                $pricerange = array_merge($priceget, $pricetemp);
                RexDisplay::assign('pricerange', $pricerange);
            }
        }

        //get main categories tree
        $this->manager = RexFactory::manager('pCatalog');
        $this->manager->getUpList($this->entity->id, RexFactory::entity('pCatalog'));
        $navCategoryList = array_reverse($this->manager->getCollection());
        if (sizeof($navCategoryList) > 0) {
            $this->manager->getByWhere('`id` IN ('.implode(',', $navCategoryList).') ORDER BY `gorder`');
            RexDisplay::assign('navCategoryList', $this->manager->getCollection());
        }
        //RexPage::setTitle(RexSettings::get('site_slogan'));
        $product_id_arr = array();
        if ($rexRequestModal || $rexRequest) {
            $manager = RexFactory::manager('cart');
            $manager->getData();
            $cartList = $manager->_collection;

            $product_id_arr = $colorProductCart = array();

            foreach ($cartList as $key => $item) {
                $product_id_arr[] = $item->product_id;
                $colorProductCart[$item->product_id.':'.$item->attributes] = 1;
            }

            RexDisplay::assign('productListCart', $product_id_arr);
            RexDisplay::assign('colorProductCart', $colorProductCart);
        }
        if ($rexRequestModal) {
            if ($rexRequestModal === 'list') {
                setcookie('modal', 'list', time() + 3600, '/');
                $response['content'] = RexDisplay::fetch('pcatalog/product.list.tpl');
//                $response['level'] = RexDisplay::fetch('pcatalog/product.list.level.inc.tpl');
            } else {
                setcookie('modal', 'block', time() + 3600, '/');
                $response['content'] = RexDisplay::fetch('pcatalog/product.block.tpl');
//                $response['level'] = RexDisplay::fetch('pcatalog/product.block.level.inc.tpl');
            }
            RexResponse::response($response);
        }
        if (isset($_COOKIE['modal']) && $_COOKIE['modal'] == 'block'){
            RexDisplay::assign('modal', true);
        }

        if ($rexRequest && !$rexRequestModal) {
            if (isset($_COOKIE['modal']) && $_COOKIE['modal'] == 'block'){
                $reponse['content'] = RexDisplay::fetch('pcatalog/product.block.inc.tpl');
//                $reponse['level'] = RexDisplay::fetch('pcatalog/product.block.level.inc.tpl');
            } else {
                $reponse['content'] = RexDisplay::fetch('pcatalog/product.list.inc.tpl');
//                $reponse['level'] = RexDisplay::fetch('pcatalog/product.list.level.inc.tpl');
            }
            RexResponse::response($reponse);
        }
    }

    function getBestseller() //smarty func
    {
        $this->_featured(array(
            'prefix' => 'featured',
            'count' => 5,
            'type' => 'bestseller' //TODO: определение category_id
        ));
    }

    function getFeatured() //smarty func
    {
        $this->_featured(array(
            'prefix' => 'featured',
            'count' => 6,
            'type' => 'bestseller'
        ));
        $this->_featured(array(
            'prefix' => 'featured',
            'count' => 6,
            'type' => 'new'
        ));
    }

	function _featured($aParams)
	{
		if (isset($aParams['category_id']) and $aParams['category_id'] > 0) {
			$this->entity = RexFactory::entity('pCatalog');
			if (!$this->entity->get($aParams['category_id'])) {
				exit;
			}

			//get all subcategories
			$this->manager = RexFactory::manager('pCatalog');
			$this->manager->getSubCategoriesList($this->entity->id, 1);
			$categoryList = $this->manager->struct;
			if ($categoryList and sizeof($categoryList) > 0) {
				$fullList = $categoryList;
				$fullList[] = $this->entity->id;
			} else {
				$fullList = array($this->entity->id);
			}
		} else {
			$fullList = false;
		}

		//show product list
		$productManager = RexFactory::manager('product');
		$productManager->getByFeaturedList($fullList, 0, $aParams['count'], $aParams['type']);
		$productList = $productManager->getCollection();
		$imageList = $productManager->images;
		$categoryList = $productManager->categories;
		$brandList = $productManager->brands;

        $skuManager = RexFactory::manager('sku');

        $color = array();
        foreach ($productList as $key => $value) {
            $skuByColor = $skuManager->getSkusFrontMainPage($value['id']);
            
            if ($skuByColor) {
                $color[$value['id']] = $skuByColor;
            }
        }

		if (sizeof($productList) > 0) {
			RexDisplay::assign($aParams['type'].'_productList', 	$productList);
			RexDisplay::assign($aParams['type'].'_imageList', 	$imageList);
			RexDisplay::assign($aParams['type'].'_categoryList', $categoryList);
            RexDisplay::assign($aParams['type'].'_brandList',     $brandList);
			RexDisplay::assign($aParams['type'].'_color', $color);
		}
	}

    function getSearch()
    {
        $rexRequest = false;

        if (RexResponse::isRequest()) {
            $rexRequest = true;
            RexResponse::init();
        }

        $rexRequestModal = Request::get('skinDefault', false);

        $zapros = Request::get('q', false);
        $page = Request::get('page', 1);

        if ($_SERVER['REQUEST_URI'] == RexRoute::getUrl(array('route' => 'shop_search')) && $page == 1) {
             RexRoute::location(array('route' => 'shop_search_one', 'q' => $zapros));
        }

        //
        $category = Request::get('category', false);
        $zapros = urldecode(urldecode($zapros));
        $q = $zapros;

        RexPage::setTitle(RexLang::get('catalog.search.title'));

        if (!$q or strlen(trim($q)) < 2) {
            RexRoute::location(array('route' => 'shop_full_list'));
        }
        $q = preg_replace('#[^a-zа-я0-9-\s\'\"]#isu', '', $q);
        RexDisplay::assign('q', $q);

        $pattern = '/^([0-9]+)-([0-9]+)$/';
        preg_match($pattern, $q, $matches);

        $sku_id = false;

        if ($matches) {
            $q = $matches[0];
            $sku_id = $matches[2];
            setcookie('sku_id', $sku_id, time() + 18000, '/', RexConfig::get('Project', 'cookie_domain'));
        } else {
            $pattern = '/^([0-9]+)$/';
            preg_match($pattern, $q, $matches);
            if ($matches) {
                $sku_id = true;
            }
        }

        if ($category) {
            XSession::set('search_category', $category);
        } else {
            XSession::remove('search_category');
        }

        //show product list
        $perPage = RexSettings::get('per_page');
        $pagerObj = new PagerObj('pager', $perPage, $page);

        $productManager = RexFactory::manager('product');
        $productManager->getByCategorySearch($pagerObj->getFrom(), $pagerObj->getPerPage(), $q, $sku_id, $category);
        $productList = $productManager->getCollection();
        if ($_SERVER['REMOTE_ADDR'] == '77.120.163.26') {
            //\sys::dump($productList);exit;
        }
        $imageList = $productManager->images;
        $categoryList = $productManager->categories;
        $brandList = $productManager->brands;


        if(sizeof($productList) === 0) {
            RexDisplay::assign('notFound', true);
        }

        if (sizeof($productList) == 1 && $page == 1) {
            RexRoute::location(array('mod' => 'product', 'act' => 'default', 'cat_alias' => $categoryList[$productList[0]['id']]->alias, 'task' => $productList[0]['id']));
        } else if (sizeof($productList) >= 1) {
            $skuManager = RexFactory::manager('sku');

            $count = $productManager->getCountCotegorySearch($q, $sku_id, $category);
            $count_more = $count - ($page * $perPage);
            $count_next = $count_more > $perPage ? $perPage : $count_more;

            if ($rexRequest) {
                $reponse['count_next'] = $count_next;
            }

            RexDisplay::assign('count_next', $count_next);

            $color = array();
            foreach ($productList as $key => $value) {
                $skuByColor = $skuManager->getSkusFrontMainPage($value['id']);

                if ($skuByColor) {
                    $color[$value['id']] = $skuByColor;
                }
            }

            RexDisplay::assign('prodColor', $color);

            RexDisplay::assign('productList', $productList);
            //\sys::dump($productList);exit;
            RexDisplay::assign('imageList', $imageList);
            RexDisplay::assign('categoryList', $categoryList);
            RexDisplay::assign('brandList', $brandList);

            $pagerObj->setCount($productManager->getByCategorySearchCount($q));
            $pagerObj->generatePages();
            RexDisplay::assign($pagerObj->name, $pagerObj);
            RexDisplay::assign('pager_count', count($pagerObj->pages));

            $page = Request::get('page', 1);
            $perPage = RexSettings::get('per_page');

            $count_more = $productManager->getByCategorySearchCount($q) - ($page * $perPage);
            $count_next = $count_more > $perPage ? $perPage : $count_more;

            RexDisplay::assign('count_next', $count_next);

            //\sys::dump($pagerObj->count);exit;
            if (isset($_COOKIE['modal'])){
                RexDisplay::assign('modal', true);
            }
            $product_id_arr = array();
            if ($rexRequestModal || $rexRequest) {
                $manager = RexFactory::manager('cart');
                $manager->getData();
                $cartList = $manager->_collection;

                $product_id_arr = $colorProductCart = array();

                foreach ($cartList as $key => $item) {
                    $product_id_arr[] = $item->product_id;
                    $colorProductCart[$item->product_id.':'.$item->attributes] = 1;
                }

                RexDisplay::assign('productListCart', $product_id_arr);
                RexDisplay::assign('colorProductCart', $colorProductCart);
            }
            if ($rexRequestModal) {
                if ($rexRequestModal === 'list') {
                    setcookie('modal', 'list', time() + 3600, '/');
                    $response = RexDisplay::fetch('pcatalog/product.list.tpl');
//                    $reponse['level'] = RexDisplay::fetch('pcatalog/product.list.level.inc.tpl');
                } else {
                    setcookie('modal', 'block', time() + 3600, '/');
                    $response = RexDisplay::fetch('pcatalog/product.block.tpl');
//                    $reponse['level'] = RexDisplay::fetch('pcatalog/product.block.level.inc.tpl');
                }
                RexResponse::response($response);
            }
            if (isset($_COOKIE['modal']) && $_COOKIE['modal'] == 'block'){
                RexDisplay::assign('modal', true);
            }

            if ($rexRequest && !$rexRequestModal) {
                if (isset($_COOKIE['modal']) && $_COOKIE['modal'] == 'block'){
                    $reponse['content'] = RexDisplay::fetch('pcatalog/product.block.inc.tpl');
//                    $reponse['level'] = RexDisplay::fetch('pcatalog/product.block.level.inc.tpl');
                } else {
                    $reponse['content'] = RexDisplay::fetch('pcatalog/product.list.inc.tpl');
//                    $reponse['level'] = RexDisplay::fetch('pcatalog/product.list.level.inc.tpl');
                }
                RexResponse::response($reponse);
            }
        }
    }

    function getList() //smarty func
    {
        RexDisplay::assign('valuta', RexSettings::get('dolar_rate', 1));
        RexDisplay::assign('filter_kurs', RexSettings::get('filter_kurs', '$'));
        $catalog_block = RexSettings::get('catalog_block', false);

        if (!isset($_COOKIE['modal'])) {
            if ($catalog_block === 'list') {
                setcookie('modal', 'list', time() - 3600, '/');
            } else {
                setcookie('modal', 'block', time() - 3600, '/');
            }
        }

        $pCatalogManager = RexFactory::manager('pCatalog');
        $pCatalogManager->getByWhere('`active` = 1 AND `level` = 0 AND is_menu = 1 ORDER BY `sorder` ASC');
        $res = $pCatalogManager->getCollection();

        RexDisplay::assign('mainCategoryList', $res);

        if ($res) {
            foreach ($res as &$catalog) {
                $pCatalogManager->getByWhere('`pid` = '.$catalog['id'].' AND `active` = 1 AND `level` = 1 ORDER BY `gorder` ASC');
                $catalog['cat_list'] = $pCatalogManager->getCollection();
                if ($catalog['cat_list']) {
                    foreach ($catalog['cat_list'] as &$catalog2) {
                        $pCatalogManager->getByWhere('`pid` = '.$catalog2['id'].' AND `active` = 1 AND `level` = 2 ORDER BY `gorder` ASC');
                        $catalog2['cat_list']['level2'] = $pCatalogManager->getCollection();
                    }
                }
            }
        }

        RexDisplay::assign('treeList', $res);
        //\sys::dump($res);exit;
    }
}