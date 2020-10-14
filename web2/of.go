package main

import (
	"fmt"
	"net/http"
	"strconv"

	"github.com/gin-contrib/sessions"
	"github.com/gin-contrib/sessions/cookie"
	"github.com/gin-gonic/gin"
	"github.com/wonderivan/logger"
)

func MoneyToData(money int32) int32 {
	var data int32
	data = 89 + (20-money)*100
	return data
}
func index_handler(c *gin.Context) {
	var current_money int32
	current_money = 20
	var target_data int32
	target_data = 10492
	session := sessions.Default(c)
	if session.Get("message") == nil {
		session.Set("message", "")
	}
	message := session.Get("message")
	cm := session.Get("current_money")
	if cm == nil {
		logger.Info("current money empty, new user")
		session.Set("current_money", current_money)
	} else {
		current_money = cm.(int32)
		logger.Info("current money exists:", current_money)
	}
	if MoneyToData(current_money) >= target_data {
		session.Set("show", "1")
	} else {
		session.Set("show", "0")
	}
	session.Save()
	c.HTML(http.StatusOK, "index.html", gin.H{
		"current_money": current_money,
		"current_data":  MoneyToData(current_money),
		"message":       message,
		"show":          MoneyToData(current_money) >= target_data,
		"target_data":   target_data,
	})
}
func buy_handler(c *gin.Context) {
	var current_money int32
	current_money = 20
	inputVal := c.PostForm("num")
	fmt.Println(inputVal)
	session := sessions.Default(c)
	session.Set("message", "")
	//check session
	session.Set("message", "")
	cm := session.Get("current_money")
	if cm == nil {
		logger.Info("current money empty, new user")
		session.Set("current_money", current_money)
	} else {
		current_money = cm.(int32)
		logger.Info("current money exists:", current_money)
	}

	buyVal, error := strconv.ParseInt(inputVal, 10, 32)
	bv := int32(buyVal)
	if error != nil {
		session.Set("message", "可疑行为？")
		logger.Info("parse int failed")
		return
	}
	fmt.Println(buyVal)
	if bv > current_money {
		session.Set("message", "可疑行为！")
		logger.Info("buy val > current money")
		return
	} else {
		originalVal := session.Get("current_money")
		ov := originalVal.(int32)
		fmt.Println("curent money:", ov)
		ov -= bv
		fmt.Println("now money:", ov)
		session.Set("current_money", ov)
		/*
			bv := buyVal.(int32)
			//oldVal := strconv.Atoi(string(originalVal))
			if error!= nil {
				c.String(200,"oops")
				return
			}
			newVal := string(ov+buyVal)
			c.String(200,"current VAL:"+newVal)
			return
		*/
	}
	session.Save()
}
func flagvid(c *gin.Context) {
	session := sessions.Default(c)
	show := session.Get("show")
	if show == nil {
		c.Redirect(307, "/")
		return
	}
	s := show.(string)
	if s != "1" {
		c.Redirect(307, "/")
		return
	} else {
		c.File("./flag.mp4")
	}
}
func main() {
	g := gin.Default()
	g.LoadHTMLGlob("./templates/*")
	store := cookie.NewStore([]byte("secret"))
	g.Use(sessions.Sessions("go-session", store))
	g.GET("/web2", index_handler)
	g.POST("/web2", buy_handler, index_handler)
	g.GET("/web2/flag.mp4", flagvid)
	g.Run("127.0.0.1:8100")
}
