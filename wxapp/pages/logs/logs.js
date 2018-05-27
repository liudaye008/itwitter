Page({
  data: {
    canIUse: wx.canIUse('button.open-type.getUserInfo'),
    gonggao:''
  },
  onLoad: function () {
    var self = this;
    wx.request({
      url: 'https://twitter.mbook.vip/system/gonggao',
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        self.setData({
          gonggao: res.data[0].value,
        })
      }
    })
  },
  bindGetUserInfo: function (e) {
    console.log(e.detail.userInfo)
  },
  copyTBL: function (e) {
    var self = this;
    wx.setClipboardData({
      data: 'xing615742973',
      success: function (res) {
        // self.setData({copyTip:true}),  
        wx.showModal({
          title: '提示',
          content: '复制成功',
          success: function (res) {
            if (res.confirm) {
              console.log('确定')
            } else if (res.cancel) {
              console.log('取消')
            }
          }
        })
      }
    });
  }  
})