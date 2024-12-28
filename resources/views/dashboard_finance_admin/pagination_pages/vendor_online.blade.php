@foreach($OnlineItems as $ot)
                                          <tr>
                                              <td data-label="Type of Earning">{{$ot->name}}</td>
                                              <td data-label="Date">{{$ot->datetime['date']}}</td>
                                              <td data-label="Time">{{$ot->datetime['time']}}</td>
                                              <td data-label="Earning">Rs. {{$ot->price}}</td>

                                            </tr>
                                            @endforeach
                                            {{ $OnlineItems->links('pagination::bootstrap-4') }}
