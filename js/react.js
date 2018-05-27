//import React, { Component } from 'react';
const typeAPI = 'http://react/directorywebservicehandler.php?function=get_type';
const directoriesurl = 'http://react/directorywebservicehandler.php?';
const getConfigs = {method : 'GET',
                    headers : { 'Accept' : 'application/json', 
                                'Content-Type': 'application/json'}
                    }
const DEFAULT_QUERY = 'redux';

class Directory extends React.Component {
    constructor(props){
        super(props);
        this.state = {
            type: [],
            activetype : 'hotel',
            directories: [],
            directoriesAPI : directoriesurl.concat('function=get_directories&params[type]=hotel')
        }
        //this.handleCheck = this.handleCheck.bind(this)
        this.handleChange = this.handleChange.bind(this);
    }
    componentDidMount() {
        this.getType()
        this.displayDirectory()
    }
    handleCheck(){
        this.setState({activetype:'hotel'})
        this.setState({directoriesAPI : directoriesurl.concat('function=get_directories&params[type]=',this.state.activetype)})
    }
    getType(){
        fetch(typeAPI,getConfigs)
        .then(response => {return response.json()})
        .then(data => {
            const liclass = ' list-group-item';
            let category = Object.keys(data).map((key)=><a href='#'  key={'link'+key}  onClick={() => this.handleChange(key,'get_directories')}> <li key={key} className={liclass}>{data[key]}</li></a>);
            return this.setState({type: category})
            })
    }
    handleChange(e,func,otherparam = '') {
        this.setState({activetype : e},()=>{
            if(otherparam){
                if(otherparam == 'searchin')
                    var searchkey =document.getElementById('searchDirectory').value;
                    otherparam ='&params[searchin]=name&params[searchkey]='+searchkey;
            }
            this.setState({ directoriesAPI: directoriesurl.concat('function=',func,'&params[type]=',this.state.activetype,otherparam)}, () => {
                this.displayDirectory()}
            )
         }
        );
      }
    displayDirectory(){
        fetch(this.state.directoriesAPI,getConfigs)
        .then(response => {return response.json()})
        .then(data =>{
            if(typeof data.error != 'undefined'){
            console.log(data.error)
                return this.setState({directories : <span>No Records</span>})
            }
          let directories =  Object.keys(data).map((key)=>
            <li className="list-group-item" key={key}>
                <div className="directory-list">
                  <h5  className="directory-header-info">{data[key].name}</h5>
                  <div className="directory-info">
                      <span className="address">
                      <span className="fa fa-map-marker faicons"></span> {data[key].address}</span> <br/>
                      <span className="phone"><span className="fa fa-phone faicons"></span>{data[key].phonenumber}</span>
                  </div>
                </div>
             </li>
            );
            return this.setState({directories: directories });
        })
    }
    render(){
        const type = this.state.type
        const directories = this.state.directories
        return (
            <div>
                <div className="row">
                    <div className="col-md-2">
                        <div className="logo-txt">LF</div>
                    </div>
                    <div className="col-md-8">
                        <div className="input-group mb-2 mr-sm-2">
                            <div className="input-group-prepend">
                                <div className="input-group-text searchbutton"><li className="fa fa-search"></li></div>
                            </div>
                            <input type="text" className="form-control searchinput" id="searchDirectory"  onChange={() => this.handleChange(this.state.activetype,'search_directory','searchin')} placeholder="Search"/>
                        </div>
                    </div>

                </div>
                <div className="row">
                    <div className="col-2">
                        <ul className="list-group list-group-flush directorycateg" >
                            {type}
                        </ul>
                    </div>
                    <div className="col-8">
                        <div className="container-fluid directorylist">
                            <ul className="list-group list-group-flush">
                                {directories}
                            </ul>
                        </div>  
                    </div>
                </div>
            </div>
        )
    }
    
}
class Checboox extends React.Component{
    constructor(props){ 
        super(props)
        this.state ={
            checked: false
        }
        this.handleCheck = this.handleCheck.bind(this)
    }
    handleCheck(){
        this.setState({
            checked: !this.state.checked
        })
    }
    render(){
        var msg
        if(this.state.checked){
            msg ='checked'
        }else{
            msg= 'nope'
        }
        return(
            <div><input type="checkbox" onChange={this.handleCheck}/>
            <p>This box {msg} </p>
            </div>
        )
    }
}
ReactDOM.render(
    <Directory/>,
    document.getElementById('directory-container')
)
