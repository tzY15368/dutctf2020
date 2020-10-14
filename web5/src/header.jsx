import React,{Component} from 'react'
import {PageHeader} from "antd";

export default class Header extends Component {
    render(){
        return (
            <div>
                <PageHeader
                    title={"填写并提交信息以0元购得flag"}
                    className="site-page-header"
                />
            </div>
        )
    }
}