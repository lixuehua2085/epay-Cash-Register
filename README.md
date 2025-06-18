<div align="center">
  
# 易收银台
![Version](https://img.shields.io/badge/当前版本-V0.0.1Beta-brightgreen.svg)
![GitHub License](https://img.shields.io/github/license/lixuehua2085/epay-Cash-Register)

</div>

## 亮点
使一个易支付接口为多个业务提供支付服务，更优雅地使用易支付<br>
注意，本系统依旧在**开发中**，请勿在正式环境使用<br>
**v0.0.1-beta更新**<br>
1.完成基本支付功能<br><br>
## 安装方法(v0.0.1-beta) <br>
将项目文件拷贝进web服务器，将trade.qdl导入数据库，最后在config文件夹中修改相关数据库和信息
## config文件夹内容
config.json<br>
包含网站名、支付通道信息<br>
database.php<br>
数据库相关信息<br>
Communication_Key.php<br>
收银台通讯密钥<br>
## 使用方法<br>
### 创建订单
使用 GET 或 HTML表单POST 至 Cash_Register.php<br>
**例** https://example.com/Cash_Register.php?trade_name=测试商品&trade_amount=1&return_url=https://example.org/return.php&return_type=sync&trade_no=2025061805078888
此操作用于业务后台向收银台发起订单
| 参数           | 是否必填 | 例子                             | 介绍                   |
|--------------|------|--------------------------------|----------------------|
| trade_name   | 否    | 测试商品                           | 商品名，默认为"收银台支付"       |
| trade_amount | 是    | 1                              | 支付金额                 |
| return_url   | 是    | https://example.com/return.php | 收银台的回调地址             |
| return_type  | 否    | sync                           | 收银台回调方式，可选sync和async |
| trade_no     | 是    | 2025061805078888               | 商户订单号                |

### 跳转支付
使用 GET 或 HTML表单POST 访问 pay.php<br>
**例** https://example.com/pay.php?trade_no=2025061805078888
此操作用于用户前端前往收银台支付
| 参数           | 是否必填 | 例子                             | 介绍                   |
|--------------|------|--------------------------------|----------------------|
| trade_no   | 是    | 2025061805078888                           | 商户订单号，用于查找订单       |
### 回调格式
编写中
