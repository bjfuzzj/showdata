<?php
class Page
{
    public static function getNavLink($template, $currentpage, $pagesize, $totalrecords) 
    {/*{{{*/
        if (($pagesize <= 0) || ($totalrecords <= 0)) {
            return '';
        }
    
        //计算总页数
        $totalpages = ceil($totalrecords / $pagesize);
        if ($totalpages <= 1) return '';
    
        //更正当前页范围
        if ($currentpage > $totalpages) $currentpage = $totalpages;
        if ($currentpage < 1) $currentpage = 1;
    
        //计算前后页
        $prevpage = $currentpage - 1;
        $nextpage = $currentpage + 1;
    
        //处理静态数据
        $t1 = array("'##TOTALPAGES##'i", "'##TOTALRECORDS##'i");
        $t2 = array($totalpages, $totalrecords);
        $template = preg_replace($t1, $t2, $template);
    
        //处理前一页
        if ($prevpage > 0) {
            $pr = null;
            while (preg_match("/\\{##PREVPAGELINK:[^\\}]*\\}/i", $template, $pr) > 0) {
                $temp = preg_replace("'##PAGE##'i", $prevpage, $pr[0]);
                $temp = preg_replace("/\\{##PREVPAGELINK:([^\\}]*)\\}/i","\\1", $temp);
                $template = str_replace($pr[0], $temp, $template);
            }
        } else {
            $template = preg_replace("/\\{##PREVPAGELINK:[^\\}]*\\}/i", "", $template);
        }
    
        //处理下一页
        if ($nextpage <= $totalpages) {
            while (preg_match("/\\{##NEXTPAGELINK:[^\\}]*\\}/i", $template, $pr) > 0) {
                $temp = preg_replace("'##PAGE##'i", $nextpage, $pr[0]);
                $temp = preg_replace("/\\{##NEXTPAGELINK:([^\\}]*)\\}/i","\\1", $temp);
                $template = str_replace($pr[0], $temp, $template);
            }
        } else {
            $template = preg_replace("/\\{##NEXTPAGELINK:[^\\}]*\\}/i", "", $template);
        }
    
        //处理首页
        if ($currentpage != 1) {
            while (preg_match("/\\{##FIRSTPAGELINK:[^\\}]*\\}/i", $template, $pr) > 0) {
                $temp = preg_replace("'##PAGE##'i", 1, $pr[0]);
                $temp = preg_replace("/\\{##FIRSTPAGELINK:([^\\}]*)\\}/i","\\1", $temp);
                $template = str_replace($pr[0], $temp, $template);
            }
        } else {
            $template = preg_replace("/\\{##FIRSTPAGELINK:[^\\}]*\\}/i", "", $template);
        }
    
        //处理最后页
        if ($currentpage != $totalpages) {
            $pr = null;
            while (preg_match("/\\{##LASTPAGELINK:[^\\}]*\\}/i", $template, $pr) > 0) {
                $temp = preg_replace("'##PAGE##'i", $totalpages, $pr[0]);
                $temp = preg_replace("/\\{##LASTPAGELINK:([^\\}]*)\\}/i","\\1", $temp);
                $template = str_replace($pr[0], $temp, $template);
            }
        } else {
            $template = preg_replace("/\\{##LASTPAGELINK:[^\\}]*\\}/i", "", $template);
        }
    
        //处理页条
        while (preg_match("/(\\{##PAGELINK:(\\d+):(\\d+):(\\d+):([^:]*):([^\\}]*)\\})/i", $template, $pr) > 0) {
            //第一块结束页
            $r2 = $pr[2];
            //第二块起始页
            $r3 = $currentpage - ceil(($pr[3] - 1) / 2);
            //第二块结束页
            $r4 = $currentpage + floor(($pr[3] - 1) / 2);
            //第三块起始页
            $r5 = $totalpages - $pr[4] + 1;
    
            //修正第二块起始结束位置(当前页处于起始或结束区域内时)
            if ($r3 <= 0) {
                $r4 -= ($r3 - 1);
                $r3 = 1;
            }
            if ($r4 > $totalpages) {
                $r3 -= ($r4 - $totalpages);
                $r4 = $totalpages;
            }
    
            $temp = '';
            $i = 0;
            while ($i < $totalpages) {
                $i++;
                if ((($i >= 1) && ($i <= $r2)) || (($i >= $r3) && ($i <= $r4)) || (($i >= $r5) && ($i <= $totalpages))) {
                    //显示页码
                    if ($i == $currentpage) {
                        $pr2 = null;
                        preg_match("/\\{##CURRENTPAGELINK:[^\\}]*\\}/i", $template, $pr2);
                        $temp .= preg_replace("/\\{##CURRENTPAGELINK:([^\\}]*)\\}/i","\\1", preg_replace("'##PAGE##'i", $currentpage, $pr2[0]));
                    } else {
                        $temp .= preg_replace("'##PAGE##'i", $i, $pr[6]);
                    }
                } elseif (($i > $r2) && ($i < $r3)) {
                    //跳过第一段至第二段, 显示...
                    if  ($r2 > 0) {
                        $temp .= '<a>...</a>';
                    }
                    $i = $r3 - 1;
                } elseif (($i > $r4) && ($i < $r5)) {
                    //跳过第二段至第三段, 显示...
                    if ($r5 <= $totalpages) {
                        $temp .= '<a>...</a>';
                    }
                    $i = $r5 - 1;
                }
            }
            $template = preg_replace("/\\{##CURRENTPAGELINK:[^\\}]*\\}/i", "", $template);
            $template = str_replace($pr[0], $temp, $template);
        }
    
        $template = preg_replace("/##PAGE##/i", $currentpage, $template);
        return $template;
    }/*}}}*/
    
    public static function getPageNavTemplate($pageurl, $n1 = 2, $n2 = 5, $n3 = 1, $forward = true, $postfix = '') 
    {/*{{{*/
        $temp  = '<nav><ul class="pagination">';
        $temp .= '{##PrevPageLink:<li><a href="'.$pageurl.'##page##'.$postfix.'" class="p_num">上一页</a></li>}';
        $temp .= '<li>{##CurrentPageLink:<a class="p_curpage" rel="true">##page##</a>}</li>';
        $temp .= '<li>{##PageLink:'."$n1:$n2:$n3:".'##page##:<a href="'.$pageurl.'##page##'.$postfix.'" class="p_num">##page##</a>}</li>';
        $temp .= '{##NextPageLink:<li><a href="'.$pageurl.'##page##'.$postfix.'" class="p_num">下一页</a></li>}';
        $temp .= '<li><a class="p_text" rel="true">共&nbsp;##totalpages##&nbsp;页</a></li>';
        if ($forward) {
            $temp .= '<li><a class="p_text">转到</a></li>';
            $temp .=<<<EOF
            <li><div class="input-group col-md-2">
      <input type="text" class="form-control" size="2" name="custompage" onkeydown="if(event.keyCode==13) {window.location='?1=1&amp;page='+this.value+''; return false;}">
      <span class="input-group-btn">
        <button class="btn btn-default" type="button" onclick="window.location='?1=1&amp;page='+document.getElementsByName('custompage').item(0).value+''; return false;">Go!</button>
      </span>
    </div></li>
EOF;
        }
        $temp .= '</ul></nav>';
        return $temp;
    }/*}}}*/


    
    public static function ajaxPageNavEventBind($listenerSelector, $containerSelector, $loadMoreAction, $jsCallBackAfterLoad='', $appendPosition='')
    {/*{{{*/
        if($appendPosition == '')
        {
            $appendPosition = 'append';
        }
        $html = "<script type='text/javascript'>
                    $(function (){
                        var container = $('".$containerSelector."');
                        $('".$listenerSelector."').each(function (){
                            $(this).bind('click', (function (btn){
                                var nowPage = 1, nextPage, isLoading, isFinished, data, action = '".$loadMoreAction."';
                                return function (e){
                                    if (isLoading){
                                        $('#isLoadingImg').show();
                                        return;
                                    }
                                    if (isFinished){
                                        $('#isLoadingImg').hide();
                                        return;
                                    }
                                    $('#isLoadingImg').show();

                                    isLoading = true;
                                    nextPage = parseInt(nowPage, 10) + 1;
                                    $.get(action+nextPage, function(result){
                                        try {
                                            data = JSON.parse(result);
                                            container.".$appendPosition."(data.content);
                                            nowPage = data.pageInfo.nowPage;
                                            if (data.hasOwnProperty('loadUrl') && data.loadUrl != '')
                                            {
                                                action = data.loadUrl;
                                            }
                                            if (nowPage >= data.pageInfo.pages) {
                                                isFinished = true;
                                                $(btn).hide();
                                                $('#isLoadingImg').hide();
                                            }
                                            ".$jsCallBackAfterLoad."
                                        } catch (e) {
                                            console.error && console.error(action+nextPage);
                                            console.error && console.error('response is not a paresable result');
                                        }
                                        isLoading = false;
                                    });
                                    e.preventDefault();
                                };
                            })(this));
                        });
                    });
                </script>";
        return $html;
    }/*}}}*/

    public static function ajaxPageNavTemplate($template, $nowPage, $pages, $loadUrl='')
    {/*{{{*/
        $template = str_replace(array("\n", "\r"), array('', ''), $template);
        header('Content-Type: application/json;'); 
        header("Content-Type: text/html; charset=UTF-8");
        $content = mb_convert_encoding($template, "UTF-8");

        $res = array(
            'content' => $content,
            'loadUrl' => $loadUrl,
            'pageInfo' => array(
                'nowPage' => $nowPage,
                'pages' => $pages,
            ),
        );
        return json_encode($res);
    }/*}}}*/

    //获取页面url，根据变量数组
    public static function getPageUrlByVars($pageUrl, Array $vars)
    {/*{{{*/
        $appendUrls = array();
        foreach($vars as $key => $value)
        {
            if(is_array($value))
            {
                foreach($value as $v)
                {
                    $appendUrls[] = $key.'[]='.$v;
                }
            }
            else
            {
                $appendUrls[] = $key.'='.$value;
            }
        }
        $appendUrl = implode('&', $appendUrls);

        if(false == strpos($pageUrl, '?'))
        {
            $pageUrl .= '?'.$appendUrl;
        }
        else if(false == strpos($pageUrl, '&'))
        {
            $pageUrl .= $appendUrl;
        }
        else
        {
            $pageUrl .= '&'.$appendUrl;
        }

        return $pageUrl;
    }/*}}}*/

    //网站地图分页模板 wap @author mln
    public static function getPageWebsiteWapTemplate($pageurl, $n1 = 2, $n2 = 5, $n3 = 1, $forward = true, $postfix = '')
    {/*{{{*/
        $temp  = '<div class="p_bar">';
        $temp .= '{##PrevPageLink:<a href="'.$pageurl.'1'.$postfix.'" class="p_num">首页</a>}&nbsp;';
        $temp .= '{##PrevPageLink:<a href="'.$pageurl.'##page##'.$postfix.'" class="p_num">上一页</a>}&nbsp;';
        $temp .= '{##CurrentPageLink:<a class="page_cur" rel="true">##page##</a>}';
        $temp .= '{##PageLink:'."$n1:$n2:$n3:".'##page##:<a href="'.$pageurl.'##page##'.$postfix.'" class="page_turn_a">##page##</a>}';
        $temp .= '&nbsp;{##NextPageLink:<a href="'.$pageurl.'##page##'.$postfix.'" class="p_num">下一页</a>}';
        $temp .= '&nbsp;{##NextPageLink:<a href="'.$pageurl.'##totalpages##'.$postfix.'" class="p_num">尾页</a>}';
        $temp .= '</div>';
        return $temp;
    }/*}}}*/


}
