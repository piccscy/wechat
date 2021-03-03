<?php

namespace WeWork\Api;

use WeWork\Traits\HttpClientTrait;

class Crm
{
    use HttpClientTrait;

    /**
     * 获取配置了客户联系功能的成员列表
     *
     * 企业和第三方服务商可通过此接口获取配置了客户联系功能的成员列表。
     * @return array
     */
    public function getFollowUserList(): array
    {
        return $this->httpClient->get('externalcontact/get_follow_user_list');
    }

    /**
     * 获取客户列表
     * 
     * 企业可通过此接口获取指定成员添加的客户列表。客户是指配置了客户联系功能的成员所添加的外部联系人。
     * 没有配置客户联系功能的成员，所添加的外部联系人将不会作为客户返回。
     * 
     * @param string $userid
     * @return array
     */
    public function list(string $userid): array
    {
        return $this->httpClient->get('externalcontact/list', ['userid' => $userid]);
    }

    /**
     * 获取客户详情
     *
     * 业可通过此接口，根据外部联系人的userid，拉取客户详情。
     * 
     * @param string $external_userid
     * @return array
     */
    public function getExternalContact(string $external_userid): array
    {
        return $this->httpClient->get('externalcontact/get', [
            'external_userid' => $external_userid
        ]);
    }

    /**
     * 修改客户备注信息
     *
     * 企业可通过此接口修改指定用户添加的客户的备注信息。
     * 
     * @param string $userid    企业成员的userid
     * @param string $external_userid   外部联系人userid
     * @param string $remark    此用户对外部联系人的备注
     * @param string $description   此用户对外部联系人的描述
     * @param string $remark_company    此用户对外部联系人备注的所属公司名称
     * @param string $remark_mobiles    此用户对外部联系人备注的手机号
     * @param string $remark_pic_mediaid    备注图片的mediaid，
     * @return array
     */
    public function updateExternalContactRemark(
        string $userid,
        string $external_userid,
        string $remark,
        string $description,
        string $remark_company,
        string $remark_mobiles,
        string $remark_pic_mediaid
    ): array {
        return $this->httpClient->get('externalcontact/remark', [
            'userid' => $userid,
            'external_userid' => $external_userid,
            'remark' => $remark,
            'description' => $description,
            'remark_company' => $remark_company,
            'remark_mobiles' => $remark_mobiles,
            'remark_pic_mediaid' => $remark_pic_mediaid
        ]);
    }

    /**
     * 获取企业标签库
     *
     * 企业可通过此接口获取企业客户标签详情。
     * 
     * @return array
     */
    public function getCorpTagList(array $tag_id): array
    {
        return $this->httpClient->postJson('externalcontact/get_corp_tag_list', [
            'tag_id' => $tag_id
        ]);
    }

    /**
     * 添加企业客户标签
     *
     * 企业可通过此接口向客户标签库中添加新的标签组和标签。
     * 
     * @param string $group_id
     * @param string $group_name    标签组名称，最长为30个字符
     * @param int $order    标签组次序值。order值大的排序靠前。有效的值范围是[0, 2^32)
     * @param array $tag tag.name   添加的标签名称，最长为30个字符
     *                   tag.order  标签次序值。order值大的排序靠前。有效的值范围是[0, 2^32)
     * @return array
     */
    public function addCorpTag(string $group_id, string $group_name, int $order, array $tag): array
    {
        return $this->httpClient->postJson('externalcontact/add_corp_tag', [
            'group_id' => $group_id,
            'group_name' => $group_name,
            'order' => (int) $order,
            'tag' => $tag
        ]);
    }

    /**
     * 编辑企业客户标签
     *
     * 企业可通过此接口编辑客户标签/标签组的名称或次序值。
     * 
     * @param string $tag_id    标签或标签组的id列表
     * @param string $tag_name  新的标签或标签组名称，最长为30个字符
     * @param int $order    标签/标签组的次序值。order值大的排序靠前。有效的值范围是[0, 2^32)
     * @return array
     */
    public function editCorpTag(string $tag_id, string $tag_name, int $order): array
    {
        return $this->httpClient->postJson('externalcontact/edit_corp_tag', [
            'id' => $tag_id,
            'name' => $tag_name,
            'order' => (int) $order
        ]);
    }

    /**
     * 删除企业客户标签
     *
     * 企业可通过此接口删除客户标签库中的标签，或删除整个标签组。
     * 
     * @param string $tag_id    标签或标签组的id列表
     * @param string $tag_name  新的标签或标签组名称，最长为30个字符
     * @param int $order    标签/标签组的次序值。order值大的排序靠前。有效的值范围是[0, 2^32)
     * {
     *     "tag_id": [
     *         "TAG_ID_1",
     *         "TAG_ID_2"
     *     ],
     *     "group_id": [
     *         "GROUP_ID_1",
     *         "GROUP_ID_2"
     *     ]
     * }
     * @return array
     */
    public function delCorpTag(string $tag_id, string $group_id): array
    {
        return $this->httpClient->postJson('externalcontact/del_corp_tag', [
            'tag_id' => $tag_id,
            'group_id' => $group_id
        ]);
    }

    /**
     * 编辑客户企业标签
     *
     * 企业可通过此接口为指定成员的客户添加上由企业统一配置的标签。
     * 
     * @param string $userid    添加外部联系人的userid
     * @param string $external_userid    外部联系人userid
     * @param array $add_tag    要标记的标签列表
     * @param array $remove_tag    要移除的标签列表
     * 请确保external_userid是userid的外部联系人。
     * add_tag和remove_tag不可同时为空。
     * 同一个标签组下现已支持多个标签
     * @example
     * {
     *     "userid":"zhangsan",
     *     "external_userid":"woAJ2GCAAAd1NPGHKSD4wKmE8Aabj9AAA",
     *     "add_tag":["TAGID1","TAGID2"],
     *     "remove_tag":["TAGID3","TAGID4"]
     * }
     * @return array
     */
    public function markTag(string $userid, string $external_userid,
        array $add_tag, array $remove_tag
    ): array
    {
        return $this->httpClient->postJson('externalcontact/mark_tag', [
            'userid' => $userid,
            'external_userid' => $external_userid,
            'add_tag' => $add_tag,
            'remove_tag' => $remove_tag
        ]);
    }

    /**
     * 获取客户群列表
     *
     * 该接口用于获取配置过客户群管理的客户群列表。
     * 
     * @param int $status_filter    群状态过滤。0:所有列表, 1:离职待继承, 2:离职继承中, 3:离职继承完成, 默认为0
     * @param array $owner_filter   群主过滤。如果不填，表示获取全部群主的数据
     * @param array $userid_list	用户ID列表。最多100个
     * @param array $partyid_list	部门ID列表。最多100个
     * @param int $offset	分页，偏移量
     * @param int $limit	分页，预期请求的数据量，取值范围 1 ~ 1000
     * @example
     * {
     *     "status_filter": 0,
     *     "owner_filter": {
     *         "userid_list": ["abel"],
     *         "partyid_list": [7]
     *     },
     *     "offset": 0,
     *     "limit": 100
     * }
     * 
     * @return array
     * {
     *     "errcode": 0,
     *     "errmsg": "ok",
     *     "group_chat_list": [{
     *         "chat_id": "wrOgQhDgAAMYQiS5ol9G7gK9JVAAAA",
     *         "status": 0
     *     }, {
     *         "chat_id": "wrOgQhDgAAcwMTB7YmDkbeBsAAAA",
     *         "status": 0
     *     }]
     * }
     * 
     */
    public function groupchatList(int $status_filter, array $owner_filter, int $offset, int $limit): array
    {
        return $this->httpClient->postJson('externalcontact/groupchat/list', [
            'status_filter' => $status_filter,
            'owner_filter' => $owner_filter,
            'offset' => $offset,
            'limit' => $limit
        ]);
    }

    /**
     * 获取客户群详情
     *
     * 通过客户群ID，获取详情。包括群名、群成员列表、群成员入群时间、入群方式。
     * （客户群是由具有客户群使用权限的成员创建的外部群）
     * 
     * @param string $chat_id   客户群ID
     * @example
     * {
     *     "chat_id":"wrOgQhDgAAMYQiS5ol9G7gK9JVAAAA"
     * }
     * 
     * @return array
     * {
     *     "errcode": 0,
     *     "errmsg": "ok",
     *     "group_chat": {
     *         "chat_id": "wrOgQhDgAAMYQiS5ol9G7gK9JVAAAA",
     *         "name": "销售客服群",
     *         "owner": "ZhuShengBen",
     *         "create_time": 1572505490,
     *         "notice" : "文明沟通，拒绝脏话",
     *         "member_list": [{
     *             "userid": "abel",
     *             "type": 1,   // 成员类型。1:企业成员, 2:外部联系人
     *             "join_time": 1572505491,
     *             "join_scene": 1  // 入群方式。1:由成员邀请入群（直接邀请入群）, 2:由成员邀请入群（通过邀请链接入群）, 3:通过扫描群二维码入群
     *         }, {
     *             "userid": "sam",
     *             "type": 1,
     *             "join_time": 1572505491,
     *             "join_scene": 1
     *         }, {
     *             "userid": "wmOgQhDgAAuXFJGwbve4g4iXknfOAAAA",
     *             "type": 2,
     *             "join_time": 1572505491,
     *             "join_scene": 1
     *         }]
     *     }
     * }
     * 
     */
    public function getGroupchat(string $chat_id): array
    {
        return $this->httpClient->postJson('externalcontact/groupchat/get', [
            'chat_id' => $chat_id
        ]);
    }

    /**
     * 添加企业群发消息任务
     *
     * 企业可通过此接口添加企业群发消息的任务并通知客服人员发送给相关客户或客户群。
     * （注：企业微信终端需升级到2.7.5版本及以上）
     * 注意：调用该接口并不会直接发送消息给客户/客户群，需要相关的客服人员操作以后才会实际发送
     * （客服人员的企业微信需要升级到2.7.5及以上版本）
     * 同一个企业每个自然月内仅可针对一个客户/客户群发送4条消息，超过限制的用户将会被忽略。
     * 
     * @param string $chat_type         群发任务的类型，默认为single，表示发送给客户，group表示发送给客户群
     * @param string $external_userid	客户的外部联系人id列表，仅在chat_type为single时有效，
     *                                  不可与sender同时为空，最多可传入1万个客户
     * @param string $sender    发送企业群发消息的成员userid，当类型为发送给客户群时必填
     * @param string $text.content	消息文本内容，最多4000个字节
     * @param string $image.media_id	图片的media_id，可以通过素材管理接口获得
     * @param string $image.pic_url	图片的链接，仅可使用上传图片接口得到的链接
     * @param string $link.title	图文消息标题
     * @param string $link.picurl	图文消息封面的url
     * @param string $link.desc     图文消息的描述，最多512个字节
     * @param string $link.url      图文消息的链接
     * @param string $miniprogram.title	小程序消息标题，最多64个字节
     * @param string $miniprogram.pic_media_id  小程序消息封面的mediaid，封面图建议尺寸为520*416
     * @param string $miniprogram.appid 小程序appid，必须是关联到企业的小程序应用
     * @param string $miniprogram.page  小程序page路径
     * 
     * @example
     * {
     *     "chat_type":"single",
     *     "external_userid": [
     *         "woAJ2GCAAAXtWyujaWJHDDGi0mACAAAA",
     *         "wmqfasd1e1927831123109rBAAAA"
     *     ],
     *     "sender":"zhangsan",
     *     "text": {
     *         "content":"文本消息内容"
     *     },
     *     "image": {
     *         "media_id": "MEDIA_ID",
     *         "pic_url":"http://p.qpic.cn/pic_wework/3474110808/7a6344sdadfwehe42060/0"
     *     },
     *     "link": {
     *         "title": "消息标题",
     *         "picurl": "https://example.pic.com/path",
     *         "desc": "消息描述",
     *         "url": "https://example.link.com/path"
     *     },
     *     "miniprogram": {
     *         "title": "消息标题",
     *         "pic_media_id": "MEDIA_ID",
     *         "appid": "wx8bd80126147dfAAA",
     *         "page": "/path/index.html"
     *     }
     * }
     * 
     * @return array
     * {
     *     "errcode": 0,
     *     "errmsg": "ok",
     *     "fail_list":["wmqfasd1e1927831123109rBAAAA"],
     *     "msgid":"msgGCAAAXtWyujaWJHDDGi0mAAAA"
     * }
     * 
     */
    public function addMsgTemplate(string $chat_type, array $external_userid, string $sender,
        array $text, array $image, array $link, array $miniprogram): array
    {
        return $this->httpClient->postJson('externalcontact/add_msg_template', [
            'chat_type' => $chat_type,
            'external_userid' => $external_userid,
            'sender' => $sender,
            'text' => $text,
            'image' => $image,
            'link' => $link,
            'miniprogram' => $miniprogram
        ]);
    }

    /**
     * 获取企业群发消息发送结果
     *
     * 企业可通过该接口获取到添加企业群发消息任务的群发发送结果。
     * 
     * @param string $msgid     群发消息的id，通过添加企业群发消息模板接口返回
     * @example
     * {
     *     "msgid": "msgGCAAAXtWyujaWJHDDGi0mACAAAA"
     * }
     * 
     * @return array
     * {
     *     "errcode": 0,
     *     "errmsg": "ok",
     *     "check_status": 1,
     *     "detail_list": [
     *         {
     *             "external_userid": "wmqfasd1e19278asdasAAAA",
     *             "chat_id":"wrOgQhDgAAMYQiS5ol9G7gK9JVAAAA",
     *             "userid": "zhangsan",
     *             "status": 1,
     *             "send_time": 1552536375
     *         }
     *     ]
     * }
     * 
     */
    public function getGroupMsgResult(string $msgid): array
    {
        return $this->httpClient->postJson('externalcontact/get_group_msg_result', [
            'msgid' => $msgid
        ]);
    }

    /**
     * 发送新客户欢迎语
     *
     * 企业微信在向企业推送添加外部联系人事件时，会额外返回一个welcome_code，企业以此为凭据调用接口，即可通过成员向新添加的客户发送个性化的欢迎语。
     * 为了保证用户体验以及避免滥用，企业仅可在收到相关事件后20秒内调用，且只可调用一次。
     * 如果企业已经在管理端为相关成员配置了可用的欢迎语，则推送添加外部联系人事件时不会返回welcome_code。
     * 每次添加新客户时可能有多个企业自建应用/第三方应用收到带有welcome_code的回调事件，但仅有最先调用的可以发送成功。
     * 后续调用将返回41051（externaluser has started chatting）错误，请用户根据实际使用需求，合理设置应用可见范围，避免冲突。
     * 
     * @param string $welcome_code	    通过添加外部联系人事件推送给企业的发送欢迎语的凭证，有效期为20秒
     * @param string $text.content	    消息文本内容,最长为4000字节
     * @param string $image.media_id	图片的media_id，可以通过素材管理接口获得
     * @param string $image.pic_url	    图片的链接，仅可使用上传图片接口得到的链接
     * @param string $link.title	    图文消息标题，最长为128字节
     * @param string $link.picurl		图文消息封面的url
     * @param string $link.desc		    图文消息的描述，最长为512字节
     * @param string $link.url		    图文消息的链接
     * @param string $miniprogram.title	        小程序消息标题，最长为64字节
     * @param string $miniprogram.pic_media_id	小程序消息封面的mediaid，封面图建议尺寸为520*416
     * @param string $miniprogram.appid	    	小程序appid，必须是关联到企业的小程序应用
     * @param string $miniprogram.page	    	小程序page路径
     * 
     * @example
     * {
     *     "welcome_code":"CALLBACK_CODE",
     *     "text": {
     *         "content":"文本消息内容"
     *     },
     *     "image": {
     *         "media_id": "MEDIA_ID",
     *         "pic_url":"http://p.qpic.cn/pic_wework/3474110808/7a6344sdadfwehe42060/0"
     *     },
     *     "link": {
     *         "title": "消息标题",
     *         "picurl": "https://example.pic.com/path",
     *         "desc": "消息描述",
     *         "url": "https://example.link.com/path"
     *     },
     *     "miniprogram": {
     *         "title": "消息标题",
     *         "pic_media_id": "MEDIA_ID",
     *         "appid": "wx8bd80126147dfAAA",
     *         "page": "/path/index.html"
     *     }
     * }
     * 
     * text、image、link和miniprogram四者不能同时为空；
     * text与另外三者可以同时发送，此时将会以两条消息的形式触达客户
     * image、link和miniprogram只能有一个，如果三者同时填，则按image、link、miniprogram的优先顺序取参，也就是说，如果image与link同时传值，则只有image生效。
     * media_id和pic_url只需填写一个，两者同时填写时使用media_id，二者不可同时为空。
     * 
     * @return array
     * {
     *     "errcode": 0,
     *     "errmsg": "ok",
     * }
     * 
     */
    public function sendWelcomeMsg(string $welcome_code, string $text,
                                array $image, array $link, array $miniprogram): array
    {
        return $this->httpClient->postJson('externalcontact/send_welcome_msg', [
            'welcome_code' => $welcome_code,
            'text' => $text,
            'image' => $image,
            'link' => $link,
            'miniprogram' => $miniprogram
        ]);
    }

    /**
     * 群欢迎语素材管理
     */

    /**
     * 获取离职成员的客户列表
     */
}
