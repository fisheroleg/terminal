<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Dataloader {

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model("data_model");
        $this->source = array();
        $this->dest = array();
        $this->markets_res = array(); //Market id to array id
        $this->products = array();  //Product id to array id
        $this->visited = array();
        $this->start=0;
        $this->depth=0;
        $this->box=array();
        $this->c_items=array();
        $this->c_dist=0;
        $this->f=array();
        $this->found=false;
        $this->d = 0.01;
        $this->a = 0.2;
        $this->t = 15;
        $this->k = 5;
        $this->dist = array();
        $this->edges = array();
        $this->way = array();
        $this->way_res = array();
        $this->max_dist = 0;
        $this->inf = 100000;
        $this->min_dist = $this->inf;
        $this->min_item = $this->inf;
        $this->L = 0;
        //$this->markets_ids;
        //$this->products_ids;
    }
    
    public function get_distance($lat1,$lng1,$lat2,$lng2)
    {
        $dLat = deg2rad($lat2-$lat1);
        $dLng = deg2rad($lng2-$lng1);
        $a = sin($dLat/2) * sin($dLat/2) +
                   cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
                   sin($dLng/2) * sin($dLng/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return $c;
    }
    
    public function fetch_id_column($products,$ids)
    {
        $ret = array();
        foreach ($ids as $key=>$id)
        {
            if ( isset( $products[$id] ) ) $ret[$key] = $products;
            else $ret[$key] = 0;
        }
        return $ret;
    }
    
    public function find_distances()
    {
        foreach ($this->markets as $id1=>$market)
        {
            foreach ($this->markets as $id2=>$market)
            {
                $this->dist[$id1][$id2] = $this->get_distance( $this->coords[$id1]->lat, $this->coords[$id1]->lng , $this->coords[$id2]->lat, $this->coords[$id2]->lng );
                $this->f[$id1][$id2] = $this->d;
                if ($this->dist[$id1][$id2] == 0) $this->dist[$id1][$id2] = $this->inf;
                if ($this->min_dist > $this->dist[$id1][$id2]) $this->min_dist = $this->dist[$id1][$id2];
                if ($this->max_dist < $this->dist[$id1][$id2] and $this->dist[$id1][$id2]!=$this->inf) $this->max_dist = $this->dist[$id1][$id2];
            }
        }
        $this->c_dist = $this->max_dist - $this->min_dist;
    }
    
    public function ant($ver)
    {
        $this->depth++;
        $min = array();//$this->inf;
        $mp = -1;
        //$buy = -1;
        //$sell = -1;
        //$item = -1;
        $buy = array();
        $dif_val = array();
        $sell = array();
        $item = array();
        $this->operation[$ver] = array();
        
        $this->visited[$ver] = true;    //Поточна вершина відвідана
        
        $sum = 0;
        $pos = array();
        
        foreach ($this->source[$ver] as $key=>$product) //Для кожної можливості купити
        {
            //$buy = array();
            //$sell = array();
            //$item = array();
            $buy[$this->start] = 0;
            $sell[$this->start] = 0;
            $item[$this->start] = 0;
        
            if( $this->source[$ver][$key] <= 0) continue;
            foreach ($this->markets_res as $sid=>$id)
            {
                if ($id != $ver && !$this->visited[ $id ])    //Для кожного іншого маркету
                {
                    $dif_items = ($this->dest[$id][$key] - $this->source[$ver][$key]) / $this->c_items[$key];
                    $dif_items *= $dif_items > 0;
                    //$dif_items *= 1000;
                    //$dif_items = 1-$dif_items;
                    $dif_dist = ($this->dist[$id][$ver] - $this->min_dist) / $this->c_dist;
                    //echo "<hr>".$dif_items.' between '.$ver.' and '.$id;
                    $min[$id] = $dif_dist*$this->coeff_dist + 1/($dif_items+0.00001)*(1-$this->coeff_dist);
                    $dif = 1/($min[$id]+0.0001)*$this->f[$ver][$id];
                    $sum += $dif;
                    $pos[$id] = $sum;
                    
                    //Додати продукт в кошик
                    $set = false;
                    if($this->dest[$id][$key] - $product > 0)
                    {
                        if(!empty($this->operation[$ver]))
                        {
                            foreach ($this->operation[$ver] as $i=>$op)
                            {
                                if($op['item'] == $this->products[$key]->name)
                                {
                                    //echo print_r($op).'<br>';
                                    if($op['sell'] - $op['buy'] < $this->dest[$id][$key] - $this->source[$id][$key])
                                    {
                                        $this->operation[$ver][$i] = array('id'=>$id,
                                                                            'buy'=>$this->source[$ver][$key],
                                                                            'sell'=>$this->dest[$id][$key],
                                                                            'item'=>$this->products[$key]->name);
                                        $set = true;
                                    }
                                }
                            }
                        }
                        
                        if(!$set) array_push( $this->operation[$ver] , array('id'=>$id,
                                                                    'buy'=>$this->source[$ver][$key],
                                                                    'sell'=>$this->dest[$id][$key],
                                                                    'item'=>$this->products[$key]->name)); //Купити при можливому перевезенні в інші міста
                    }
                    
                    //echo $id."<br>";
                    $buy[$id] = $this->source[$ver][$key];
                    $sell[$id] = $this->dest[$id][$key];
                    $item[$id] = $key;
                    $dif_val[$id] = $dif_items;
                }
            }
        }
        //echo var_dump($buy) . "<br>";
        $r = mt_rand(0, mt_getrandmax() - 1) / mt_getrandmax() * $sum;
        foreach ($pos as $id=>$val)
        {
            
            if ($r < $val)
            {
                $mp = $id;
                //echo " + ".$dif_val[$mp] . " + ";
                //echo " + ".$buy[$mp] . " " . $sell[$mp] . " + ";
                break;
            }
        }
        
        if (empty($min) || $this->depth == $this->max_depth+1)
        {
            $mp = $this->start;
            $item = 0;
            $this->found=true;
        }
        
        $this->chain[$ver] = array('start'=>$ver, 'next'=>$mp, 'buy'=>$buy[$mp], 'sell'=>$sell[$mp], 'item'=>$item[$mp]!=0 ? $this->products[$item[$mp]]->name : 0);
        //if($sell[$mp] - $buy[$id] > 0) array_push($this->way,$mp);
        $this->operation[$ver] = array();
        array_push( $this->operation[$ver] , $mp );
        array_push($this->edges,array($ver,$mp));
        $ret = (!empty($min) and !$this->found and $mp!=-1) ? $this->ant($mp) + $min[$mp] : 0;
        
        return $ret;
    }
    
    private function dec_f()
    {
        foreach ($this->f as $i=>$col)
        {
            foreach ($col as $j=>$row)
            {
                $this->f[$i][$j] *= 1-$this->a;
            }
        }
    }
    
    private function count_mark()
    {
        $mark = 0;
        foreach ($this->operation as $ops)
        {
            if(!empty($ops))
            {
                foreach ($ops as $op)
                {
                    $dif = $op['sell']-$op['buy'];
                    if($dif>0) $mark +=$dif;
                }
            }
        }
        return $mark;
    }
    
    public function ACO($start)
    {
        $this->operation = array();
        $this->edges = array();
        $this->greedy($start);
        $this->L = $this->count_mark();
        
        //print json_encode($this->way) . " => " . $this->L . "<hr>";
        
        $this->chain_res = $this->chain;
        $this->operation_res = $this->operation;
        foreach ($this->edges as $keys)
        {
            $this->f[$keys[0]][$keys[1]] += 0.1;
            
        }

        $this->way_res = $this->way;
        
        for ($i = 0; $i < 10; $i++)   //Поки колонія активна
        {
            $this->update = array();
            $this->ls = array();
                
            for ($j = 0; $j < count($this->markets_res); $j++)   //Для кожного мурахи
            {
                $this->visited = $this->visited_all;
                $this->edges = array();
                $this->operation = array();
                $this->way = array();
                $this->chain = array();
                $this->found = false;
                
                $this->start = $j;
                $this->depth=0;
                array_push($this->way, $this->start);
                array_push($this->operation, true);
                
                $this->ant($this->start);
                
                $Ln = $this->count_mark();
                //print var_dump.'<hr>';
                //print json_encode($this->way) . " => " . $Ln . " against " . $this->L . "<hr>";
                
                array_push($this->update, $this->edges);
                array_push($this->ls, $Ln);
                
                if ($this->L < $Ln)
                {
                    print "success";
                    $this->L = $Ln;
                    $this->way_res = $this->way;
                    $this->chain_res = $this->chain;
                    $this->operation_res = $this->operation;
                }
            }
            
            foreach ($this->update as $way)
            foreach ($way as $keys)
            {
                $this->f[$keys[0]][$keys[1]] += $Ln/($this->L+0.00001);
            }
            
            $this->dec_f();
            
        }
        $this->way = $this->way_res;
        $this->chain = $this->chain_res;
        $this->operation = $this->operation_res;
        
        //echo "<hr>";
        //print json_encode($this->way) . " => " . $Ln . "<hr>";
        //print json_encode($this->way); 
        //           echo " = ".$this->L."<hr>";
    }
    
    public function greedy($ver)
    {
        if($this->found) return 0;
        $this->depth++;
        $min = $this->inf;
        $mp = -1;
        $buy = -1;
        $sell = -1;
        $item = -1;
        $dif_val = -1;
        
        $this->operation[$ver] = array();
        //$this->source[$ver][0] = 1;
        //$this->dest[$ver][0] = 1;   //Варіант "не перевозити нічого"
        $this->visited[$ver] = true;    //Поточна вершина відвідана
        
        $vis = true;
        foreach ($this->visited as $el)
        {
            $vis *= $el;
        }
        if ($vis) {$this->visited[$this->markets_res[$this->start]] = false; $this->found = true;}
        //echo var_dump($this->dest[$ver]).'<hr>';
        
        foreach ($this->source[$ver] as $key=>$product) //Для кожної можливості купити
        {
            //if($this->found) 
            if( $this->source[$ver][$key] <= 0) continue;
            //echo var_dump($this->source[$ver][$key]).'<br>';
            //array_push($this->box, array() );
            foreach ($this->markets_res as $sid=>$id)
            {
                if($this->dest[$ver][$key] - $this->source[$ver][$key] > 0 ) array_push( $this->operation[$ver] , array('id'=>$ver,
                                                                    'buy'=>$this->source[$ver][$key],
                                                                    'sell'=>$this->dest[$ver][$key],
                                                                    'item'=>$this->products[$key]->name));
                
                if ($id != $ver && !$this->visited[ $id ])    //Для кожного іншого маркету
                {
                    //print $ver." to ".$id." with ".$key." s=".$product."   d=".$this->dest[$id][$key]."<hr>";
                    $dif_items = ($this->dest[$id][$key] - $this->source[$ver][$key]) / $this->c_items[$key];
                    $dif_items *= $dif_items > 0;
                    $dif_items = 1-$dif_items;
                    //print $dif_items.'<br>';
                    $dif_dist = ($this->dist[$id][$ver] - $this->min_dist) / $this->c_dist;
                    
                    $dif = $dif_dist*$this->coeff_dist + $dif_items*(1-$this->coeff_dist);
                    
                    
                    $set = false;
                    if($this->dest[$id][$key] - $product > 0)
                    {
                        if(!empty($this->operation[$ver]))
                        {
                            foreach ($this->operation[$ver] as $i=>$op)
                            {
                                if($op['item'] == $this->products[$key]->name)
                                {
                                    //echo print_r($op).'<br>';
                                    if($op['sell'] - $op['buy'] < $this->dest[$id][$key] - $this->source[$id][$key])
                                    {
                                        $this->operation[$ver][$i] = array('id'=>$id,
                                                                            'buy'=>$this->source[$ver][$key],
                                                                            'sell'=>$this->dest[$id][$key],
                                                                            'item'=>$this->products[$key]->name);
                                        $set = true;
                                    }
                                }
                            }
                        }
                        
                        if(!$set) array_push( $this->operation[$ver] , array('id'=>$id,
                                                                    'buy'=>$this->source[$ver][$key],
                                                                    'sell'=>$this->dest[$id][$key],
                                                                    'item'=>$this->products[$key]->name)); //Купити при можливому перевезенні в інші міста
                    }
                    if ($dif < $min)
                    {
                        //print $dif.'<br>';
                        $min = $dif;
                        $mp = $id;
                        $buy = $this->source[$ver][$key];
                        $sell = $this->dest[$id][$key];
                        //print $buy.' = > '.$sell.'<br>';
                        $item = $key;
                        $dif_val = $dif_items;
                    }
                }
            }
        }
        
        if ($min==$this->inf || $this->depth == $this->max_depth+1)
        {
            $mp = $this->markets_res[$this->start];
            $item = 0;
            $this->found=true;
            //$this->visited[$mp] = false
        }
        
        //$this->chain[$ver] = array('start'=>$ver, 'next'=>$mp, 'buy'=>$buy, 'sell'=>$sell, 'item'=>$item!=0 ? $this->products[$item]->name : 0);
        
        array_push($this->way,$mp);
        
        //if($sell - $buy > 0) array_push( $this->operation[$ver] , $mp );
        array_push($this->edges,array($ver,$mp));
        //print $ver.'==('.$buy.' '.$sell.')==>'.$mp.'<br>';
        $ret = $this->greedy($mp) + $min;
        //echo $min;
        return $ret;
    }
    
    public function load($ver, $depth, $c_dist, $ignore_products, $ignore_markets)
    {
        //$this->num_markets = $this->CI->data_model->get_num_markets();
        //$this->num_markets = $this->num_markets[0]->num;
        $this->start = $ver;
        $this->max_depth = $depth - 1;
    
        $this->coeff_dist = $c_dist/100;
        
        //print $this->coeff_dist.'<br>';
        
        $this->all_products = $this->CI->data_model->get_products(-1,-1,$ignore_products);  //всі продукти
        $this->all_items = $this->CI->data_model->get_items(-1,-1,$ignore_products);    //всі айтеми
        
        $this->markets = $this->CI->data_model->get_markets($ignore_markets);  //Список міст
        
        //print_r($this->all_products);
        //print '<br><br>';
        //print_r($this->all_items);
        //print '<br><br>';
        //print_r($this->markets);
        //print '<br><br>';
        
        $i=0;
        foreach ($this->markets as $col)
        {
            $this->markets_res[$col->id] = $i;  //Вдіповідність між айді маркету та порядковим номером в алгоритмі
            $this->visited[$i] = false; //Всі вершини невідвідані
            $this->coords[$i] = (object) array (
                                      'lat' => $col->lat,
                                      'lng' => $col->lng
            );  //Координати вершини
            
            $key = $i;  //Ключ поточного маркету
            $this->source[$i][0] = 1;
            $this->dest[$i][0] = 1;
        
            if (!empty($this->all_products))    //є продукти - 
            {
                $j=1;   //Перший айтем резервуємо під нульове перевезення
                foreach ($this->all_products as $product)
                {
                    $this->source[$i][$j] = 0;
                    $this->dest[$i][$j] = 0;
                    $this->products[$j] = $product;
                    $this->products_res[$product->id] = $j; //Вдіповідність між айді продукту та порядковим номером в алгоритмі
                    $j++;
                }
            }
            
            $items = $this->CI->data_model->get_items($col->id, 0, $ignore_products);   //Айтеми, що продаються в поточному маркеті
            
            if (!empty($items))
            {
                $j=0;
                foreach ($items as $item)
                {
                    $this->source[$key][$this->products_res[$item->id]] = $item->price;
                    $j++;
                }
            }
            
            $items = $this->CI->data_model->get_items($col->id, 1, $ignore_products);   //Айтеми, що купують в поточному маркеті
            
            if (!empty($items))
            {
                $j=0;
                foreach ($items as $item)
                {
                    $this->dest[$key][$this->products_res[$item->id]] = $item->price;
                    //print $this->source[$key][$this->products_res[$item->id]].' => '.$this->dest[$key][$this->products_res[$item->id]].'<br>';
                    $j++;
                }
            }
            
            $i++;
        }
        
        $this->c_items[0] = 1;
        foreach ($this->products_res as $product)   //Коефіцієнт нормування до 0...1
        {
            $max = $this->inf; $min = 0; $this->c_items[$product] = 1;
            foreach ($this->markets_res as $market)
            {
                $dest = $this->dest[$market][$product];
                if ($dest > $min) $min = $dest;
                $source =  $this->source[$market][$product];
                if ($source < $max and $source > 0) $max = $source;
                //print $this->source[$market][$product].' => '.$this->dest[$market][$product].'<br>';
            }
            //print $max.'  '.$min.'<br>';
            if ($min - $max > 0) $this->c_items[$product] = $min - $max;
            //print $this->c_items[$product].'<br>';
        }
        
        //print_r($this->source);
        //print '<br>';
        //print_r($this->dest);
        //print '<br>';
        
        $this->way = array();
        $this->chain = array();
        $this->operation = array();
        array_push($this->way, $this->markets_res[$ver]);
        array_push($this->operation, true);
        
        $this->visited_all = $this->visited;
        
        $this->find_distances();
        
        $this->ACO($this->markets_res[$ver]);
        
        for ($i = 0; $i < count($this->way); $i++)
        {
            $this->way[$i] = array_search($this->way[$i], $this->markets_res);
        }
        
        //echo var_dump($this->operation);
        $res = array();
        
        //if($this->dest[$id][$key] - $product > 0) array_push( $this->operation[$ver] , array('id'=>$id,
        //                                                                                                 'buy'=>$this->source[$ver][$key],
        //                                                                                                 'sell'=>$this->dest[$id][$key],
        //                                                                                                 'item'=>$this->products[$key]->name); //Купити при можливому перевезенні в інші міста
        //            
        
        foreach ($this->operation as $i=>$link)
        {
            $edge = $link;
            $i = array_search($i, $this->markets_res);
            $res[$i] = array();
            foreach ($edge as $el)
            {
                //echo var_dump($el);
                $el['id'] = array_search($el['id'], $this->markets_res);
                array_push($res[$i], $el);
            }
        }
        
        $this->result['way'] = $this->way;
        $this->result['operation'] = $res;
        $this->result['chain'] = $this->CI->load->view('general/chain_view',array('chains'=>$this->operation, 'markets'=>$this->markets),true);
        
        return $this->result;
    }
    
    public function print_table($table)
    {
        $m = count($table);
        $n = count($this->all_products);
        print "<table>";
        foreach($table as $col)
        {
            print "<tr>";
            foreach($col as $row)
            {
                print "<td style='width:50px;'>".$row."</td>";
            }
            print "</tr>";
        }
        print "</table>";
    }
}