class Side_menu extends React.Component {
    render() {

        return (
            
        <li><a><i class="fa fa-money"></i> Pagos <span class="fa fa-chevron-down"></span></a>
          <ul class="nav child_menu">
            <li><a href="index.html">Pendientes</a></li>
            <li><a href="index2.html">Solventes</a></li>
            <li><a href="index3.html">Historico</a></li>
          </ul>
        </li>
          
        
        );
      }
}

ReactDOM.render(
    <Side_menu />,
    document.getElementById('react_side-menu')
);