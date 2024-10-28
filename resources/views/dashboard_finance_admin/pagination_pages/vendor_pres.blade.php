@foreach($prescriptions as $pres)
                                          <tr>
                                              <td data-label="Type of Earning">{{$pres->name}}</td>
                                              <td data-label="Date">{{$pres->datetime['date']}}</td>
                                              <td data-label="Time">{{$pres->datetime['time']}}</td>
                                              <td data-label="Earning">${{$pres->price}}</td>
              
                                            </tr>
                                            @endforeach
                                            {{ $prescriptions->links('pagination::bootstrap-4') }}