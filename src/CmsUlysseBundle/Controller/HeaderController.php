<?php
namespace CmsUlysseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class HeaderController extends Controller
{


    public function customAction()
    {


        function toArray($obj)
        {
            if (is_object($obj)) $obj = (array)$obj;
            if (is_array($obj)) {
                $new = array();
                foreach ($obj as $key => $val) {
                    $tab=explode('CmsUlysseBundle\Entity\Site',substr($key,1));
                    $valTb=substr($tab[1],1);
                    $new[$valTb] = toArray($val);
                }
            } else {
                $new = $obj;
            }

            return $new;
        }

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('CmsUlysseBundle:Site');
        $site = $repository->findOneBy(array());
        $siteVal=toArray($site);

            $style='';


        /* Convert hexdec color string to rgb(a) string
        * $map => 1 same color
        * $map => 2 inverse color
        * $map => .. .. color
        */
        function hex2rgba($color,  $hcolor = '', $map = false, $mixage = '' ,$opacity = '') {

            $default = 'rgb(0,0,0)';

            /*complementary color*/
            if($hcolor !='')
            {
                $color =$hcolor;
            }
            /* inverse color */
            if($map){
                $color = str_replace('#', '', $color);
                $rgb = '';
                for ($x=0;$x<3;$x++){
                    $c = 255 - hexdec(substr($color,(2*$x),2));
                    $c = ($c < 0) ? 0 : dechex($c);
                    $rgb .= (strlen($c) < 2) ? '0'.$c : $c;
                }
                $color= '#'.$rgb;
            }


            //Return default if no color provided
            if(empty($color)){
                return $default;
            }


            //Sanitize $color if "#" is provided
            if ($color[0] == '#' ) {
                $color = substr( $color, 1 );
            }

            //Check if color has 6 or 3 characters and get values
            if (strlen($color) == 6) {
                $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
            } elseif ( strlen( $color ) == 3 ) {
                $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
            } else {
                return $default;
            }


            //Check if opacity is set(rgba or rgb)
            //Convert hexadec to rgb
            $rgb =  array_map('hexdec', $hex);

            /* add color */
            if($mixage != ''){
                //Sanitize $color if "#" is provided
                if ($mixage[0] == '#' ) {
                    $mixage = substr( $mixage, 1 );
                }

                //Check if color has 6 or 3 characters and get values
                if (strlen($mixage) == 6) {
                    $hex2 = array( $mixage[0] . $mixage[1], $mixage[2] . $mixage[3], $mixage[4] . $mixage[5] );
                } elseif ( strlen( $mixage ) == 3 ) {
                    $hex2 = array( $mixage[0] . $mixage[0], $mixage[1] . $mixage[1], $mixage[2] . $mixage[2] );
                }
                //Check if opacity is set(rgba or rgb)
                //Convert hexadec to rgb
                $rgb2 =  array_map('hexdec', $hex2);

                $r=round(($rgb[0]+$rgb2[0])/2);
                $g=round(($rgb[1]+$rgb2[1])/2);
                $b=round(($rgb[2]+$rgb2[2])/2);

                $rgb[0]=$r;
                $rgb[1]=$g;
                $rgb[2]=$b;

            }

            if($opacity > 0 && $opacity < 1){
                $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
            } else {
                $output = 'rgb('.implode(",",$rgb).')';
            }

            //Return rgb(a) color string
            return $output;
        }



        $global_color = $siteVal['bodyColor'];//'#00007A';


        /* custom style colonne*/

        $nbColonne=$siteVal['corpsColonnes'];

        switch ($nbColonne) {

            //'middle'
            case 1 :
                $style .= ".colonneA{display:none;};";
                $style .= ".colonneB{display:block;width:100%;}";
                $style .= ".colonneC{display:none}";
                break;

            //'middle&left'
            case 2 :
                $style .= ".colonneA{display:inline-block;vertical-align:top;width: 250px;height:100%;}";
                $style .= ".colonneB{display:inline-block;vertical-align:top;width:calc(100% - 250px - 4px);} .colonneB #feature{display:none;}";
                $style .= ".colonneC{display:none;}";
                break;

            //'middle&right'
            case 3 :
                $style .= ".colonneA{display:none;}";
                $style .= ".colonneB{display:inline-block;vertical-align:top;width:calc(100% - (100% / 4) - 4px);}";
                $style .= ".colonneC{display:inline-block;vertical-align:top;width:calc(100%  / 4);} .colonneB #new{display:none;}";
                break;

            default:
                $style .= ".colonneA{display:inline-block;vertical-align:top;width: 250px;height:100%}";
                $style .= ".colonneB{display:inline-block;vertical-align:top;width:calc(100% - 250px - ((100% - 250px) / 4) ) - 8px);}.colonneB #feature{display:none;}.colonneB #new{display:none;}";
                $style .= ".colonneC{display:inline-block;vertical-align:top;width:calc((100% - 250px) / 4);}";
                break;
        }
        /* custom style header*/
        $background_color_header='';
        $background_color_header=($background_color_header == '' && $global_color != '')? hex2rgba($background_color_header,$global_color,false):$background_color_header;
        $background_color_header=(trim($background_color_header)==''||strtoupper($background_color_header[0])=='R')?$background_color_header:hex2rgba($background_color_header);

        $background_img_header='';

        $color_text_header='';
        $color_text_header=($color_text_header == '' && $global_color != '')? hex2rgba($color_text_header,$global_color,true,'',0.8):$color_text_header;
        $color_text_header=(trim($color_text_header)==''||strtoupper($color_text_header[0])=='R')?$color_text_header:hex2rgba($color_text_header);


        $style.='.header{';
        $style.=trim($background_color_header)==''?'':'background-color:'.$background_color_header.';';
        $style.= trim($background_img_header)==''?'':'background-image:'.$background_img_header.';background-repeat:no-repeat;background-size:cover;';
        $style.=trim($color_text_header)==''?'':'color:'.$color_text_header.';';
        $style.='}';



        /* custom style body */
        $background_color_body='';
        $background_color_body=($background_color_body == '' && $global_color != '')? hex2rgba($background_color_body,$global_color,true):$background_color_body;
        $background_color_body=(trim($background_color_body)==''||strtoupper($background_color_body[0])=='R')?$background_color_body:hex2rgba($background_color_body);

        $settings_background_img_body=($site->getWebPath())?'/'.$site->getWebPath():'';
        $color_text_body='';
        $color_text_body=($color_text_body == '' && $global_color != '')? hex2rgba($color_text_body,$global_color,false):$color_text_body;
        $color_text_body=(trim($color_text_body)==''||strtoupper($color_text_body[0])=='R')?$color_text_body:hex2rgba($color_text_body);

        $style.='.body{';
        $style.= trim($background_color_body)==''?'':'background-color:'.$background_color_body.';';
        $style.= trim($settings_background_img_body)==''?'':'background-image:url('.$settings_background_img_body.');background-repeat:no-repeat;background-size:cover;';
        $style.=trim($color_text_body)==''?'':'color:'.$color_text_body.';';
        $style.='}';
        $style.='h2.head{';
        $style.=trim($color_text_body)==''?'':'color:'.$color_text_body.';';
        $style.='}';


        /* custom style head top */
        $background_color_top='';
        $background_color_top=($background_color_top == '' && $global_color != '')? hex2rgba($background_color_top,$global_color,true):$background_color_top;
        $background_color_top=(trim($background_color_top)==''||strtoupper($background_color_top[0])=='R')?$background_color_top:hex2rgba($background_color_top);

        $settings_background_img_top='';
        $color_text_top='';
        $color_text_top=($color_text_top == '' && $global_color != '')? hex2rgba($color_text_top,$global_color,false):$color_text_top;
        $color_text_top=(trim($color_text_top)==''||strtoupper($color_text_top[0])=='R')?$color_text_top:hex2rgba($color_text_top);

        $style.='.header-top{';
        $style.= trim($background_color_top)==''?'':'background-color:'.$background_color_top.';';
        $style.= trim($settings_background_img_top)==''?'':'background-image:'.$settings_background_img_top.';background-repeat:no-repeat;background-size:cover;';
        $style.=trim($color_text_top)==''?'':'color:'.$color_text_top.';';
        $style.='}';

        $style.='.cssmenu ul li a, .cssmenu ul li a:hover{';
        $style.= trim($background_color_top)==''?'':'background-color:'.$background_color_top.';';
        $style.=trim($color_text_top)==''?'':'color:'.$color_text_top.';';
        $style.='}';

        /* custom style footer */

        $background_color_footer='';
        $background_color_footer=($background_color_footer == '' && $global_color != '')? hex2rgba($background_color_footer,$global_color,false):$background_color_footer;
        $background_color_footer=(trim($background_color_footer)==''||strtoupper($background_color_footer[0])=='R')?$background_color_footer:hex2rgba($background_color_footer);

        $background_img_footer='';

        $color_text_footer='';
        $color_text_footer=($color_text_footer == '' && $global_color != '')? hex2rgba($color_text_footer,$global_color,true):$color_text_footer;
        $color_text_footer=(trim($color_text_footer)==''||strtoupper($color_text_footer[0])=='R')?$color_text_footer:hex2rgba($color_text_footer);

        $style.='.footer{';
        $style.= trim($background_color_footer)==''?'':'background-color:'.$background_color_footer.';';
        $style.= trim($background_img_footer)==''?'':'background-image:'.$background_img_footer.';background-repeat:no-repeat;background-size:cover;';
        $style.=trim($color_text_footer)==''?'':'color:'.$color_text_footer.';';
        $style.='}';


        /* custom style icon */
        $background_icon='';
        $background_icon=($background_icon == '' && $global_color != '')? hex2rgba($background_icon,$global_color,true):$background_icon;
        $background_icon=(trim($background_icon)==''||strtoupper($background_icon[0])=='R')?$background_icon:hex2rgba($background_icon);

        $color_icon='';
        $color_icon=($color_icon == '' && $global_color != '')? hex2rgba($color_icon,$global_color,false):$color_icon;
        $color_icon=(trim($color_icon)==''||strtoupper($color_icon[0])=='R')?$color_icon:hex2rgba($color_icon);

        $background_iconC='';
        $background_iconC=($background_iconC == '' && $global_color != '')? hex2rgba($background_iconC,$global_color,true, '#7DB122'):$background_iconC;
        $background_iconC=(trim($background_iconC)==''||strtoupper($background_iconC[0])=='R')?$background_iconC:hex2rgba($background_iconC);


        $style.='.header .icon  a  i.fa{';
        $style.= trim($color_icon)==''?'':'color:'.$color_icon.';';
        $style.='}';
        $style.='.icon{';
        $style.= trim($background_icon)==''?'':'background:'.$background_icon.';';
        $style.='}';
        $style.='.card .icon{';
        $style.= trim($background_iconC)==''?'':'background:'.$background_iconC.' url(../../../images/cart2.png) no-repeat scroll 2px 0px;';
        $style.='}';
        $style.='.card span.actual{';
        $style.= trim($background_iconC)==''?'':'color:'.$background_iconC.';';
        $style.='}';


        /* custom style card */

        $background_color_card='';
        $background_color_card=($background_color_card == '' && $global_color != '')? hex2rgba($background_color_card,$global_color,false):$background_color_card;
        $background_color_card=(trim($background_color_card)==''||strtoupper($background_color_card[0])=='R')?$background_color_card:hex2rgba($background_color_card);

        $background_img_card='';

        $color_text_card='';
        $color_text_card=($color_text_card == '' && $global_color != '')? hex2rgba($color_text_card,$global_color,true):$color_text_card;
        $color_text_card=(trim($color_text_card)==''||strtoupper($color_text_card[0])=='R')?$color_text_card:hex2rgba($color_text_card);

        $style.='.card{';
        $style.= trim($background_color_card)==''?'':'background-color:'.$background_color_card.';';
        $style.= trim($background_img_card)==''?'':'background-image:'.$background_img_card.';background-repeat:no-repeat;background-size:cover;';
        $style.=trim($color_text_card)==''?'':'color:'.$color_text_card.';';
        $style.='}';

        /* other custom freedom */
        $setting_custom_other='';
        $style.= $setting_custom_other;


        return $this->render('CmsUlysseBundle:Main:style.html.twig',  array(
                'style' => $style ,
            )
        );
    }

    public function listAction()
    {

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('CmsUlysseBundle:Site');
        $site = $repository->findOneBy(array());



        return $this->render('CmsUlysseBundle:Main:header.html.twig',  array(
                'site'  => $site,
            )
        );
    }
}
