import React from 'react';
import Header from "./header";
import {Row, Col, Divider} from 'antd';
import CtfForm from "./CTFform";
function App() {
  return (
    <div>
        <Row>
            <Col md={{span:16,offset:4}} sm={{span:16,offset:4}} lg={{span:16,offset:4}} xs={{span:16,offset:4}}>

                <Col md={{span:20,offset:2}} sm={{span:20,offset:2}} xs={{span:20,offset:2}}>

                    <Header/>
                </Col>
                <Divider/>
                <Row>
                    <Col md={{span:20,offset:2}} sm={{span:20,offset:2}} xs={{span:20,offset:2}}>
                        <div className="panel">
                            <div className="panel-body">
                                <Col md={{span:24}} sm={{span:24}} xs={{span:24}}>
                                    <CtfForm/>
                                </Col>
                            </div>
                        </div>
                    </Col>
                </Row>
            </Col>
        </Row>

    </div>
  );
}

export default App;
