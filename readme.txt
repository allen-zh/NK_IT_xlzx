南开大学信息学院团委在线心理咨询平台


model层：

	user_model		用户的相关操作
	message_model		私信的相关操作
	article_model		文章的相关操作
	reservation_model	预约的相关操作


controller层：

	main		首页
	article		文章部分
	message		私信部分
	reservation	预约部分
	user		用户部分


view层：

	user文件夹	前台
		index.php	首页
		css文件夹	放css
		images文件夹	放图片
		js文件夹	放js
		common文件夹	放通用页面

	admin文件夹	后台
		同上



所有后台数据的合法性验证全在controller层做，model层假设传入数据都是合法的。

函数参数若是数组，则其中的项都可为空。