define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        fun: {
            binddata: function(){
                if (default_config['all_spider'] == '0')
                {
                    $('input[name="all_spider"][value="0"]').prop('checked', '');
                    $('.spider input[type="radio"]').prop('disabled', true);
                }
                else
                {
                    $('input[name="all_spider"][value="1"]').prop('checked', 'checked');
                }

                $('#c-delay').val('');
                if (typeof default_config['delay'] != 'undefined')
                {
                    $('#c-delay').val(default_config['delay']);
                }

                if (typeof default_config['limit_dir'] != 'undefined')
                {
                    var limit_dir = [];
                    $.each(default_config['limit_dir'], function(i){
                        var txt = default_config['limit_dir'][i];
                        limit_dir.push({text:txt});
                    });
                    $('#c-limit_dir').val(JSON.stringify(limit_dir));
                }

                if (typeof default_config['sitemap'] != 'undefined')
                {
                    var sitemap = [];
                    $.each(default_config['sitemap'], function(i){
                        var txt = default_config['sitemap'][i];
                        sitemap.push({text:txt});
                    });
                    $('#c-sitemap').val(JSON.stringify(sitemap));
                }
                
                if (typeof default_config['spider'] != 'undefined')
                {
                    $.each(default_config['spider'], function(i){
                        var key = default_config['spider'][i]['key'];
                        var value = default_config['spider'][i]['value'];

                        $('input[name="'+key+'"]').prop('checked', '');
                        $('input[name="'+key+'"][value="'+value+'"]').prop('checked', 'checked');
                    });
                }
            },

            reload: function(is_create){
                var data = {};
                var result = '';
                result += '# robots.txt generated at https://www.fastadmin.net/store/robotscreate.html\r\n';

                var all_spider = $('input[name="all_spider"]:checked').val();
                data['all_spider'] = all_spider;
                if (all_spider == '1')
                {
                    var spider = [];
                    $.each($('.spider'), function(i){
                        var obj = $('.spider').eq(i);
                        var text = obj.data('text');

                        var radio = obj.find('input[type="radio"]:checked');
                        var v = radio.val();
                        var k = radio.attr('name');
                        spider.push({key:k, value:v});
                        switch(v)
                        {
                            case '1':
                                result += 'User-agent: '+text+'\r\n';
                                result += 'Disallow: \r\n';
                                break;
                            case '2':
                                result += 'User-agent: '+text+'\r\n';
                                result += 'Disallow: /\r\n';
                                break;
                        }
                    });
                    data['spider'] = spider;

                    result += 'User-agent: *\r\n';
                    result += 'Disallow:\r\n';
                }

                //检索间隔
                var delay = $('#c-delay').val();
                if (delay.length > 0)
                {
                    result += 'Crawl-delay: '+delay+'\r\n';
                    data['delay'] = delay;
                }

                //限制目录
                data['limit_dir'] = [];
                var limit_dir = $('#c-limit_dir').val();
                var limit_dir_object = JSON.parse(limit_dir);
                if (limit_dir_object.length > 0)
                {
                    $.each(limit_dir_object, function(i){
                        if (limit_dir_object[i]['text'].length > 0)
                        {
                            result += 'Disallow: ' + limit_dir_object[i]['text'] + '\r\n';
                            data['limit_dir'].push(limit_dir_object[i]['text']);
                        }
                    });
                }

                //Sitemap
                data['sitemap'] = [];
                var sitemap = $('#c-sitemap').val();
                var sitemap_object = JSON.parse(sitemap);
                if (sitemap_object.length > 0)
                {
                    $.each(sitemap_object, function(i){
                        if (sitemap_object[i]['text'].length > 0)
                        {
                            result += 'Sitemap: ' + sitemap_object[i]['text'] + '\r\n';
                            data['sitemap'].push(sitemap_object[i]['text']);
                        }
                    });
                }

                $('#result').val(result);

                //写入到配置中
                if (is_create)
                {
                    console.log(data);
                    Backend.api.ajax({
                        url: 'robotscreate/robots/index',
                        data: {data:JSON.stringify(data)}
                    }, function (d) {
                        // alert(1)
                    }, function(d){
                        
                    });
                }
            }
        },
        index: function () {
            $('#c-delay_select').change(function(){
                var v = $(this).val();
                $('#c-delay').val(v);
            });

            $('input[name="all_spider"]').click(function(){
                var that = $(this);
                if (that.val() == '1')
                {
                    $('.spider input[type="radio"]').prop('disabled', false);
                }
                else
                {
                    $('.spider input[type="radio"]').prop('disabled', true);
                }
            });

            $('#js_Create').click(function(){
                Layer.confirm('生成后会更新robots.txt文件', {
                    icon: 3,
                    title: '提示'
                }, function (index) {
                    Layer.close(index);
                    Controller.fun.reload(true);
                });
            });

            $('#js_init').click(function(){
                Layer.confirm('确认初始化配置', {
                    icon: 3,
                    title: '提示'
                }, function (index) {
                    Layer.close(index);
                    var server_url = $('#c-server_url').val();
                    default_config = {"all_spider":"1","spider":[{"key":"Baiduspider","value":"0"},{"key":"Sosospider","value":"0"},{"key":"souguo_spider","value":"0"},{"key":"YodaoBot","value":"0"},{"key":"Googlebot","value":"0"},{"key":"Bingbot","value":"0"},{"key":"Slurp","value":"0"},{"key":"Teoma","value":"0"},{"key":"ia_archiver","value":"0"},{"key":"twiceler","value":"0"},{"key":"MSNBot","value":"0"},{"key":"Scrubby","value":"0"},{"key":"Robozilla","value":"0"},{"key":"Gigabot","value":"0"},{"key":"googlebot_image","value":"0"},{"key":"googlebot_mobile","value":"0"},{"key":"yahoo_mmcrawler","value":"0"},{"key":"yahoo_blogs","value":"0"},{"key":"psbot","value":"0"}],"limit_dir":["/bin/"],"sitemap":[server_url + "/sitemap.xml"]};

                    Controller.fun.binddata();
                    Controller.fun.reload(true);
                    window.location.reload();
                });
            });

            Controller.fun.binddata();
            Controller.fun.reload(false);
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});