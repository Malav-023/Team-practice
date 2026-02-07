#include<iostream>
#include<vector> 
#include<algorithm>

int main()
{    
    std::vector<int> solution; 
    int arr[4]={1,2,5,10};
    int target=39,max=3; 

    while(target!=0)
    {
        if(arr[max]<=target){target-=arr[max];solution.push_back(arr[max]);}
        else{max--;}
    }
    for(int i:solution)
    {std::cout<<i<<" "; }

    return 0; 
}