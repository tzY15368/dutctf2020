import {message} from "antd";
export function ErrorMsg(content){
    message.error(content)
};
export const SuccessMsg = (content) => {
    message.success(content);
};